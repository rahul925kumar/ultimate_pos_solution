<?php

namespace App\Http\Controllers;
use App\Contact;
use App\Http\Controllers\Controller;
use App\Customer;
use App\KittyGroup;
use App\KittyInstallment;
use App\KittyMember;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KittyGroupAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $kittyGroupRepository;



    public function index(Request $request)
    {
        if (request()->ajax()) {

            $users = KittyGroup::get();
            return Datatables::of($users)
                ->editColumn('name', '{{$name}}')
                ->editColumn('total_amount', function ($row) {
                    return number_format($row->total_amount, 2); // Format amount to 2 decimal places
                })
                ->editColumn('start_month', '{{$start_month}}')
                ->editColumn('status', function ($row) {
                    return $row->status ? '<span class="label bg-green">Active</span>' : '<span class="label bg-red">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
            <a href="' . action('App\Http\Controllers\KittyGroupAPIController@edit', [$row->id]) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
            <a href="' . action('App\Http\Controllers\KittyGroupAPIController@show', [$row->id]) . '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> View</a>
            <button data-href="' . action('App\Http\Controllers\KittyGroupAPIController@destroy', [$row->id]) . '" class="btn btn-xs btn-danger delete_group_button"><i class="glyphicon glyphicon-trash"></i> Delete</button>
        ';
                })
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->rawColumns(['action', 'status'])
                ->make(true);

        }
        return view('kitties.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = Contact::where('type', 'customer')->get();

        return view('kitties.create', compact('users'));
    }


    public function edit($id)
    {
        $kittty = KittyGroup::find($id);
        $users = Contact::where('type', 'customer')->get();

        return view('kitties.edit', compact('kittty', 'users'));
    }


    public function show(string $id)
    {
        $kittyGroup = KittyGroup::with('members.customer')->find($id);
        // return $kittyGroup;
        return view('kitties.view', compact('kittyGroup'));
    }


    public function store(Request $request)
    {


        // Step 1: Create the KittyGroup
        $group = KittyGroup::create([
            'name' => $request['kitty_detail']['name'],
            'total_amount' => $request['kitty_detail']['total_amount'],
            'start_month' => $request['kitty_detail']['start_month']
        ]);

        // Step 2: Calculate installment amount and set installment count
        $memberCount = count($request['kitty_detail']['customers']);
        $installmentAmount = $request['kitty_detail']['total_amount'] / $memberCount;
        $installmentCount = $memberCount;

        // Step 3: Set initial due date on the 10th day of the start_month
        $startMonth = $request['kitty_detail']['start_month']; // e.g., "2025-01-01"
        $initialDueDate = date('Y-m-10', strtotime($startMonth));

        // Step 4: Loop through each customer and create members and installments
        foreach ($request['kitty_detail']['customers'] as $customer) {
            // Add member to the kitty group
            $member = KittyMember::create([
                'kitty_group_id' => $group->id,
                'customer_id' => (int) $customer
            ]);

            // Create monthly installments for this member
            $dueDate = $initialDueDate; // Start from the initial due date
            for ($i = 0; $i < $installmentCount; $i++) {
                KittyInstallment::create([
                    'customer_id' => $member->customer_id,
                    'due_amount' => $installmentAmount,
                    'kitty_group_id' => $group->id,
                    'paid_amount' => 0,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                ]);

                // Move due date to the 10th of the next month
                $dueDate = date('Y-m-10', strtotime('+1 month', strtotime($dueDate)));
            }
        }

        $output = [
            'success' => 1,
            'msg' => __('Kitty Group and Installments Created Successfully.'),
        ];
        return redirect('kitties/kitties')->with('status', $output);
        // return $this->sendResponse($group->load('members'), 'Kitty Group and Installments Created Successfully.');
    }

    public function update(Request $request, $id)
    {
        // Step 1: Find the existing KittyGroup by ID
        $group = KittyGroup::findOrFail($id);

        // Step 2: Update the KittyGroup details
        $group->update([
            'name' => $request['kitty_detail']['name'],
            'total_amount' => $request['kitty_detail']['total_amount'],
            'start_month' => $request['kitty_detail']['start_month']
        ]);

        // Step 3: Recalculate installment amount and set installment count
        $memberCount = count($request['kitty_detail']['customers']);
        $installmentAmount = $request['kitty_detail']['total_amount'] / $memberCount;
        $installmentCount = $memberCount;

        // Step 4: Set initial due date on the 10th day of the start_month
        $startMonth = $request['kitty_detail']['start_month']; // e.g., "2025-01-01"
        $initialDueDate = date('Y-m-10', strtotime($startMonth));

        // Step 5: Remove previous members and installments, if needed
        $group->members()->delete(); // Delete all members
        KittyInstallment::where('kitty_group_id', $group->id)->delete(); // Delete all installments

        // Step 6: Loop through each customer and recreate members and installments
        foreach ($request['kitty_detail']['customers'] as $customer) {
            // Add member to the kitty group
            $member = KittyMember::create([
                'kitty_group_id' => $group->id,
                'customer_id' => (int) $customer
            ]);

            // Create monthly installments for this member
            $dueDate = $initialDueDate; // Start from the initial due date
            for ($i = 0; $i < $installmentCount; $i++) {
                KittyInstallment::create([
                    'customer_id' => $member->customer_id,
                    'due_amount' => $installmentAmount,
                    'kitty_group_id' => $group->id,
                    'paid_amount' => 0,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                ]);

                // Move due date to the 10th of the next month
                $dueDate = date('Y-m-10', strtotime('+1 month', strtotime($dueDate)));
            }
        }

        // return $this->sendResponse($group->load('members'), 'Kitty Group and Installments Updated Successfully.');
        $output = [
            'success' => 1,
            'msg' => __('Kitty Group and Installments Updated Successfully.'),
        ];
        return redirect('kitties/kitties')->with('status', $output);
    }



    public function destroy($id)
    {
        $group = KittyGroup::findOrFail($id);
        KittyMember::where('kitty_group_id', $group->id)->delete();
        $group->delete();
        $output = [
            'success' => true,
            'msg' => __('Kitty Group Deleted Successfully.'),
        ];
        return $output;
    }

    public function fetchCustomers()
    {
        $data = Customer::select('id', 'name')->get();
        return $data;
    }

    public function getCustomersList()
    {
        $data = Customer::select('id', 'name')->get();
        return $data;
    }

    public function fetchKittyGroupInstallments($id)
    {
        // dd($id);
        $installments = KittyInstallment::with('customer')
            ->where('kitty_group_id', $id)
            ->orderBy('due_date') // Order by due date
            ->get();

        // Step 2: Group installments by due date
        $groupedInstallments = $installments->groupBy('due_date');

        // Format response data
        $data = $groupedInstallments->map(function ($installmentsByDate, $dueDate) {
            return [
                'due_date' => $dueDate,
                'installments' => $installmentsByDate->map(function ($installment) {
                    return [
                        'installment_id' => $installment->id,
                        'due_amount' => $installment->due_amount,
                        'paid_amount' => $installment->paid_amount,
                        'status' => $installment->status,
                        'customer' => [
                            'customer_id' => $installment->customer->id,
                            'name' => $installment->customer->name,
                        ]
                    ];
                })
            ];
        })->values();

        $kittyMemebers = KittyMember::where(['kitty_group_id' => $id])->get();
        $response['installment_data'] = $data;

        $response['kitty_members'] = $kittyMemebers;
        return view('kitties.installments', compact('response'));
        // return $response;
    }

    public function selectedWinnerCreate(Request $request, $id)
    {
        $group = KittyGroup::findOrFail($id);
        KittyMember::where('kitty_group_id', $group->id)->where(['customer_id' => $request->customer_id])->update(['has_won' => 1, 'won_month' => $request->due_date]);
        $customer = Customer::findOrFail($id);
        $balance = $customer->account_balance + $group->total_amount;
        Customer::where('id', $request->customer_id)->update(['account_balance' => $balance]);
        return $this->sendResponse([], message: 'Winner Selected Successfully.');
    }

    public function changePaymentStatus(Request $request, $id)
    {
        KittyInstallment::where('customer_id', $request['customer']['customer_id'])->where('id', $request['installment_id'])->where('kitty_group_id', $id)->update(['paid_amount' => $request['due_amount'], 'status' => 1]);
        return $this->sendResponse([], message: 'Payment status updated Successfully.');
    }

}
