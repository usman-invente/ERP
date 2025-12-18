@extends('layouts.app')
@section('title', __('income.incomes'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('income.incomes')</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
                @if(auth()->user()->can('all_income.access'))
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('location_id',  __('purchase.business_location') . ':') !!}
                            {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                        </div>
                    </div>                    
                @endif
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('income_category_id',__('income.income_category').':') !!}
                        {!! Form::select('income_category_id', $categories, null, ['placeholder' =>
                        __('report.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'income_category_id']); !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('income_sub_category_id_filter',__('product.sub_category').':') !!}
                        {!! Form::select('income_sub_category_id_filter', $sub_categories, null, ['placeholder' =>
                        __('report.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'income_sub_category_id_filter']); !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('income_date_range', __('report.date_range') . ':') !!}
                        {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'income_date_range', 'readonly']); !!}
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary', 'title' => __('income.all_incomes')])
                @can('income.add')
                    @slot('tool')
                        <div class="box-tools">
                            {{-- <a class="btn btn-block btn-primary" href="{{action([\App\Http\Controllers\IncomeController::class, 'create'])}}">
                            <i class="fa fa-plus"></i> @lang('messages.add')</a> --}}
                            {{-- <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right tw-m-2"
                                href="{{action([\App\Http\Controllers\IncomeController::class, 'create'])}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg> @lang('messages.add')
                            </a> --}}
                           
                        </div>
                    @endslot
                @endcan
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="income_table">
                        <thead>
                            <tr>
                                <th>@lang('messages.action')</th>
                                <th>@lang('messages.date')</th>
                                <th>@lang('income.ref_no')</th>
                                <th>@lang('income.total_incomes_brutto')</th>
                                <th>@lang('income.total_incomes_tax')</th>
                                <th>@lang('income.income_category')</th>
                                <th>@lang('income.income_sub_category')</th>
                                <th>@lang('business.location')</th>
                                <th>@lang('income.income_note')</th>
                                <th>@lang('lang_v1.added_by')</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-gray font-17 text-center footer-total">
                                <td colspan="3"><strong>@lang('sale.total'):</strong></td>
                                <td class="footer_income_total"></td>
                                <td class="footer_total_tax"></td>
                                <td colspan="5"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endcomponent
        </div>
    </div>

</section>
<!-- /.content -->

@stop
@section('javascript')
 <script type="text/javascript">

    $(document).ready(function() {

        if ($('#income_date_range').length == 1) {
            $('#income_date_range').daterangepicker(
                dateRangeSettings, 
                function(start, end) {
                    $('#income_date_range').val(
                        start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                    );
                    income_table.ajax.reload();
                }
            );
            
            $('#income_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#product_sr_date_filter').val('');
                income_table.ajax.reload();
            });
        }

        income_table = $('#income_table').DataTable({
            processing: true,
            serverSide: true,
            fixedHeader:false,
            aaSorting: [[1, 'desc']],
            ajax: {
                url: '/incomes',
                data: function(d) {
                    d.income_category_id = $('select#income_category_id').val();
                    d.income_sub_category_id = $('select#income_sub_category_id_filter').val();
                    d.location_id = $('select#location_id').val();
                    d.start_date = $('input#income_date_range')
                        .data('daterangepicker')
                        .startDate.format('YYYY-MM-DD');
                    d.end_date = $('input#income_date_range')
                        .data('daterangepicker')
                        .endDate.format('YYYY-MM-DD');
                },
            },
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'transaction_date', name: 'transaction_date' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'final_total', name: 'final_total' },
                { data: 'tax_total', name: 'tax_total' },
                { data: 'categorie', name: 'ic.name' },
                { data: 'sub_categorie', name: 'isc.name' },
                { data: 'location_name', name: 'bl.name' },
                { data: 'additional_notes', name: 'additional_notes' },
                { data: 'added_by', name: 'usr.first_name'}
            ],
            fnDrawCallback: function(row, data, start, end, display) {
                var income_total = sum_table_col($('#income_table'), 'final-total');
                var total_tax = sum_table_col($('#income_table'), 'tax-total');

                $('.footer_income_total').html(__currency_trans_from_en(income_total));
                $('.footer_total_tax').html(__currency_trans_from_en(total_tax));

               
            },
            createdRow: function(row, data, dataIndex) {
                $(row)
                    .find('td:eq(4)')
                    .attr('class', 'clickable_td');
            },
        });

        $('select#location_id, select#income_category_id,  \
        select#income_sub_category_id_filter').on(
        'change',
        function() {
            income_table.ajax.reload();
        }
    );
    }); 

    $(document).on('click', 'a.delete_income', function(e) {
        e.preventDefault();
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_income,
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
                            income_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
        
    });
    </script>
@endsection