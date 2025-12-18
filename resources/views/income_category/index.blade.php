@extends('layouts.app')
@section('title', __('income.income_categories'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang( 'income.income_categories' )
        <small  class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang( 'income.manage_your_income_categories' )</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'income.all_your_income_categories' )])
        @slot('tool')
            <div class="box-tools">
                
                <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full btn-modal pull-right"
                    data-href="{{action([\App\Http\Controllers\IncomeCategorieController::class, 'create'])}}" 
                    data-container=".income_category_modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg> @lang('messages.add')
                </a>
            </div>
        @endslot
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="income_category_table">
                <thead>
                    <tr>
                        <th>@lang( 'income.category_name' )</th>
                        <th>@lang( 'income.category_code' )</th>
                        <th>@lang( 'messages.action' )</th>
                    </tr>
                </thead>
            </table>
        </div>
    @endcomponent

    <div class="modal fade income_category_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection
@section('javascript')
 {{-- <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script> --}}
 <script type="text/javascript">
    
    $(document).ready(function() {
        var income_cat_table = $('#income_category_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/income-categories',
            columnDefs: [
                {
                    targets: 2,
                    orderable: false,
                    searchable: false,
                },
            ],
        });     
        
        $(document).on('submit', 'form#income_category_add_form', function(e) {
            e.preventDefault();
            var data = $(this).serialize();

            $.ajax({
                method: 'POST',
                url: $(this).attr('action'),
                dataType: 'json',
                data: data,
                success: function(result) {
                    if (result.success === true) {
                        $('div.income_category_modal').modal('hide');
                        toastr.success(result.msg);
                        income_cat_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        });

        $(document).on('click', 'button.delete_income_category', function() {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_income_category,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            income_cat_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    }); 
    </script>
@endsection
