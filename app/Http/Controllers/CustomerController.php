<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
class CustomerController extends Controller
{
    //顧客一覧を表示する
    public function index(Request $request)
    {
        $customers = Customer::paginate(10);
        return view('customers.index', compact('customers'));
    }

    // 顧客登録画面の表示
    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        // バリデーション済みのデータを取得
        $validated = $request->validated();


        // データベースへ保存
        Customer::create($validated);

        return redirect()->route('customers.index');
    }

    // 顧客編集画面の表示
    public function edit(Customer $customer)
    {
        // ビューに顧客データを渡して表示
        return view('customers.edit', compact('customer'));
    }

    // 顧客情報の更新
    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        // バリデーション済みのデータを取得
        $validated = $request->validated();

        // データベースを更新
        $customer->update($validated);

        // 一覧画面や詳細画面にリダイレクト
        return redirect()
            ->route('customers.show', $customer)
            ->with('success', '顧客情報を更新しました。');
    }

    // 指定された顧客の詳細を表示する
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    //指定された顧客を削除
    public function destroy(Customer $customer)
    {
        // 顧客に紐づく案件が存在するかどうかチェック
        if ($customer->projects()->exists()) {

            // 存在する場合、削除せずエラーメッセージ付きで一覧にリダイレクト
            return redirect()
                ->route('customers.index')
                ->with('error_message', '案件が残っているため削除できません。');


        } else {
            // 案件が存在しない場のみ削除を実行
            $customer->delete();
            // 削除後のリダイレクトとフラッシュメッセージの送信
            return redirect()
                ->route('customers.index')
                ->with('success_message', '顧客情報を削除しました。');
        }

    }
}