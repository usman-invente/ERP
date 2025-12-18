@php
	$pdf_generation_for = ['Original for Buyer'];
@endphp

@foreach($pdf_generation_for as $pdf_for)
	{{-- <link rel="stylesheet" href="{{ asset('css/app.css?v='.$asset_v) }}"> --}}
	<style type="text/css">
		table.tpdf {
		  width: 100% !important;
		  border-collapse: collapse;
		  line-height: 1.1;
		}

		table.tpdf, table.tpdf tr, table.tpdf td, table.tpdf th {
		  border: 1px solid black;
		  padding-left: 10px;
		  padding-top: 6px;
		}
		.box {
			border: 1px solid black;
		}

	</style>
	<div class="width-100">
		<div class="width-100 f-left" align="center">
			<strong class="font-20">@lang('cash_book.cash_book')</strong>
		</div>
		{{-- <div class="width-50 f-left" align="right">
			<strong>{{$pdf_for}}</strong>
		</div> --}}
	</div>
	<div class="width-100 box">
		<div class="width-100 mb-10 mt-10" align="center">
		</div>
		<div class="width-40 f-left" style="text-align: center;">
			{{-- @if(!empty($logo))
	          <img src="{{$logo}}" alt="Logo" style="width: 85%; height: 60%; margin: auto;padding-left: 30px;">
	        @endif --}}
			{{-- <div>
				@if(!empty($business->logo))
					<img class="img-responsive" src="{{ url( 'uploads/business_logos/' . $business->logo ) }}" alt="Business Logo">                                    
				@endif
			</div> --}}
	        <div style="margin-left: 30px;margin-top: 0px;padding-top: 0px;">
	        	@if(!empty($location_details->custom_field1) && !empty($custom_labels['location']['custom_field_1']))
					{{$custom_labels['location']['custom_field_1']}} : {{$location_details->custom_field1}}
		        @endif
	        </div>
		</div>
		<div class="width-60 f-left" align="center" style="color: #22489B;padding-top: 5px;">
			<strong class="font-23">
	    		{!!$business->name!!}
	    	</strong>
	    	{{-- <br>
	    	{{ $location_details->name }} --}}
	        @if(!empty($cash_book->business_location->landmark))
	          <br>{{$cash_book->business_location->landmark}}
	        @endif
	        @if(!empty($location_details->city) || !empty($location_details->state) || !empty($location_details->country))
			<br>{{implode(',', array_filter([$location_details->city, $location_details->state, $location_details->country]))}}
	        @endif
	    	@if(!empty($location_details->website))
	    		<br>
	    		@lang('lang_v1.website'): 
	    		<a href="{!!$location_details->website!!}" target="_blank" style="text-decoration: none;">
					{!!$location_details->website!!}
				</a>
	    	@endif
	    	@if(!empty($location_details->email))
	    		<br>@lang('business.email'): {!!$location_details->email!!}
	    	@endif
	        @if(!empty($location_details->custom_field2) && !empty($custom_labels['location']['custom_field_2']))
	          <br>{{$custom_labels['location']['custom_field_2']}} : {{$location_details->custom_field2}}
	        @endif
	        @if(!empty($location_details->custom_field3) && !empty($custom_labels['location']['custom_field_3']))
	          <br>{{$custom_labels['location']['custom_field_3']}} : {{$location_details->custom_field3}}
	        @endif
	        @if(!empty($location_details->custom_field4) && !empty($custom_labels['location']['custom_field_4']))
	          <br>{{$custom_labels['location']['custom_field_4']}} : {{$location_details->custom_field4}}
	        @endif
		</div>
	</div>
	<table class="tpdf">
		<tr>
			<td colspan="7" align="left">
				<strong>@lang('cash_book.start_amount')
				@if(count($cash_books) > 0)
					@format_currency($cash_books[0]->sum_amount) 
				@endif </strong> 
			</td>
			{{-- <td align="left">
				@if(count($cash_books) > 0)
					@format_currency($cash_books[0]->sum_amount)
				@endif
			</td> --}}
		</tr>
		<tr style="background: #b7b9c1;">
			<td class="width-50">
				<strong>@lang('cash_book.revenue')</strong> 
			</td>
			<td class="width-50">
				<strong>@lang('cash_book.expense')</strong> 
			</td>
			<td class="width-50">
				<strong>@lang('cash_book.total')</strong> 
			</td>
			<td class="width-50">
				<strong>@lang('cash_book.receipt_no')</strong> 
			</td>
			<td class="width-50">
				<strong>@lang('cash_book.transaction_date')</strong> 
			</td>
			<td class="width-50">
				<strong>@lang('cash_book.tax_rate')</strong> 
			</td>			
			<td class="width-50">
				<strong>@lang('cash_book.booking_text')</strong> 
			</td>		
		</tr>		
		@php
			$sum_expense = 0;
			$sum_revenue = 0;
			$last_end_amount = 0;
			$start_or_transfer_amount = $cash_book[0]->sum_amount;
		@endphp
		@foreach($cash_books as $key => $cash_book)	
			@if(!$cash_book->brutto_tax_rate && !$cash_book->brutto_tax_rate_1 && !$cash_book->brutto_tax_rate_2)
				<tr>
					@php
						$expense = 0;
						$revenue = 0;					
						if($cash_book->status_cash_register == "payout"){
							$expense = $cash_book->amount;
							$sum_expense = $sum_expense + $cash_book->amount;
					@endphp
							<td class="width-50" align="right">
							</td>
							<td class="width-50" align="right">
								@format_currency($expense)
							</td>
					@php
						}
						if($cash_book->brutto_amount){
							$revenue = $cash_book->brutto_amount;
							$sum_revenue = $sum_revenue + $cash_book->brutto_amount;
					@endphp
						<td class="width-50" align="right">
							 @format_currency($revenue)
						</td>
						<td class="width-50" align="right">
						</td>
					@php					
						}
						$last_end_amount = $cash_book->sum_amount;
					@endphp
					@if($cash_book->status_cash_register != "payout" && !$cash_book->brutto_amount)
						<td class="width-50" align="right">
						</td>
						<td class="width-50" align="right">
						</td>
					@endif
					<td class="width-50" align="right">
						@format_currency($cash_book->sum_amount) 
					</td>
					<td class="width-50">
						{!! $cash_book->numbering_cash !!}
					</td>
					<td class="width-50">
						{!!  @format_date($cash_book->transaction_date) !!}
					</td>
					<td class="width-50" align="right">
					</td>
					<td class="width-50">
						{!! $cash_book->description !!} 
					</td>

				</tr>
			@endif
			@if($cash_book->brutto_tax_rate > 0)
				<tr>
					@php
						$expense = 0;
						$revenue = 0;					
						if($cash_book->status_cash_register == "payout"){
							$expense = $cash_book->amount;
							$sum_expense = $sum_expense + $cash_book->amount;
						$start_or_transfer_amount = $start_or_transfer_amount - $cash_book->brutto_tax_rate;
					@endphp
							<td class="width-50" align="right">
							</td>
							<td class="width-50" align="right">
								@format_currency($cash_book->brutto_tax_rate)
							</td>
					@php
						}
						if($cash_book->brutto_amount){
							$revenue = $cash_book->brutto_amount;
							$sum_revenue = $sum_revenue + $cash_book->brutto_amount;
						$start_or_transfer_amount = $start_or_transfer_amount + $cash_book->brutto_tax_rate;
					@endphp
							<td class="width-50" align="right">
								 @format_currency($cash_book->brutto_tax_rate)
							</td>
							<td class="width-50" align="right">
							</td>
					@php
						}
						$last_end_amount = $cash_book->sum_amount;
					@endphp
					<td class="width-50" align="right">
						@format_currency($start_or_transfer_amount) 
					</td>
					<td class="width-50">
						{!! $cash_book->numbering_cash !!}
					</td>
					<td class="width-50">
						{!!  @format_date($cash_book->transaction_date) !!}
					</td>
					<td class="width-50" align="right">
						19%
					</td>
					<td class="width-50">
						{!! $cash_book->description !!} 
					</td>
				</tr>	
			@endif
			@if($cash_book->brutto_tax_rate_1 > 0)
				<tr>
					@php
						$expense = 0;
						$revenue = 0;					
						if($cash_book->status_cash_register == "payout"){
							$expense = $cash_book->amount;
							$sum_expense = $sum_expense + $cash_book->amount;
						$start_or_transfer_amount = $start_or_transfer_amount - $cash_book->brutto_tax_rate_1;
					@endphp
							<td class="width-50" align="right">
							</td>
							<td class="width-50" align="right">
								@format_currency($cash_book->brutto_tax_rate_1)
							</td>
					@php
						}
						if($cash_book->brutto_amount){
							$revenue = $cash_book->brutto_amount;
							$sum_revenue = $sum_revenue + $cash_book->brutto_amount;
						$start_or_transfer_amount = $start_or_transfer_amount + $cash_book->brutto_tax_rate_1;
					@endphp
							<td class="width-50" align="right">
								 @format_currency($cash_book->brutto_tax_rate_1)
							</td>
							<td class="width-50" align="right">
							</td>
					@php
						}
						$last_end_amount = $cash_book->sum_amount;
					@endphp
					<td class="width-50" align="right">
						@format_currency($start_or_transfer_amount) 
					</td>
					<td class="width-50">
						{!! $cash_book->numbering_cash !!} 
					</td>
					<td class="width-50">
						{!!  @format_date($cash_book->transaction_date) !!}
					</td>
					<td class="width-50" align="right">
						7%
					</td>
					<td class="width-50">
						{!! $cash_book->description !!} 
					</td>
				</tr>
			@endif
			@if($cash_book->brutto_tax_rate_2 > 0)
				<tr>
					@php
						$expense = 0;
						$revenue = 0;					
						if($cash_book->status_cash_register == "payout"){
							$expense = $cash_book->amount;
							$sum_expense = $sum_expense + $cash_book->amount;
							$start_or_transfer_amount = $start_or_transfer_amount - $cash_book->brutto_tax_rate_2;
					@endphp
							<td class="width-50" align="right">
							</td>
							<td class="width-50" align="right">
								@format_currency($cash_book->brutto_tax_rate_2)
							</td>
					@php
						}
						if($cash_book->brutto_amount){
							$revenue = $cash_book->brutto_amount;
							$sum_revenue = $sum_revenue + $cash_book->brutto_amount;
							$start_or_transfer_amount = $start_or_transfer_amount + $cash_book->brutto_tax_rate_2;
					@endphp
							<td class="width-50" align="right">
								 @format_currency($cash_book->brutto_tax_rate_2)
							</td>
							<td class="width-50" align="right">
							</td>
					@php
						}
						$last_end_amount = $cash_book->sum_amount;
						
					@endphp
					<td class="width-50" align="right">
						@format_currency($start_or_transfer_amount) 
					</td>
					<td class="width-50">
						{!! $cash_book->numbering_cash !!}
					</td>
					<td class="width-50">
						{!!  @format_date($cash_book->transaction_date) !!}
					</td>
					<td class="width-50" align="right">
						0%
					</td>
					<td class="width-50">
						{!! $cash_book->description !!} 
					</td>
				</tr>
			@endif
		@endforeach
		<tr>
			<td class="width-50" align="right">
				@format_currency($sum_revenue)
		   	</td>
		   	<td class="width-50" align="right">
				@format_currency($sum_expense)
		   	</td>
		   	<td colspan="2" class="width-50" align="left">
				<strong> @lang('cash_book.total')</strong> 
		   	</td>
		   	<td colspan="3" class="width-50" align="left">
				<strong> @lang('cash_book.signature')</strong> 
		   	</td>
		</tr>
		<tr>
			<td class="width-50" align="right">
				@format_currency($cash_books[0]->sum_amount)
		   	</td>
		   	<td class="width-50" align="right">
				@format_currency($cash_books[0]->sum_amount + $sum_revenue - $sum_expense)
		   	</td>
		   	<td colspan="2" class="width-50" align="left">
				<strong> @lang('cash_book.start_end_sum_total')</strong> 
		   	</td>
		   	<td colspan="3" class="width-50" align="left">
				<strong> @lang('cash_book.checked_by')</strong> 
		   	</td>
		</tr>
		<tr>
			<td class="width-50" align="right">
				@format_currency($cash_books[0]->sum_amount + $sum_revenue)
		   	</td>
		   	<td class="width-50" align="right">
				@format_currency($cash_books[0]->sum_amount + $sum_revenue)
		   	</td>
		   	<td colspan="2" class="width-50" align="left">
				<strong> @lang('cash_book.soll_ist')</strong> 
		   	</td>
		   	<td colspan="3" class="width-50" align="left">
				<strong> @lang('cash_book.booked_by')</strong> 
		   	</td>
		</tr>
	</table>
	
	<div class="box">
	
	@php
		$bottom = '5px';
		// if (count($purchase->purchase_lines) >= 3) {
		// 	$bottom = '-15px';
		// }
	@endphp
	{{-- <div align="center" class="fs-10" style="position: fixed;width: 100%;bottom: {{$bottom}};text-align: center;">
		<table>
			<tr>
			</tr>
			<tr>
				<td>
					<strong>@lang('cash_book.start_amount')</strong> 
				</td>
				<td>
					<td class="width-50" align="right">
						@if(count($cash_books) > 0)
							@format_currency($cash_books[0]->sum_amount) 
						@endif
					</td>
				</td>
			</tr>
			<tr>
				<td>
					<strong>@lang('cash_book.revenue')</strong> 
				</td>
				<td>
					<td class="width-50" align="right">
						@format_currency($sum_revenue) 
					</td>
				</td>
			</tr>
			<tr>
				<td>
					<strong>@lang('cash_book.expense')</strong>
				</td>
				<td>
					<td class="width-50" align="right">
						@format_currency($sum_expense) 
					</td>
				</td>
			<tr>
				<td colspan="2">					
						<hr>				
				</td>
				<td colspan="2">					
						<hr>				
				</td>
			<tr>
				<td>
					<strong>@lang('cash_book.end_amount')</strong>
				</td>
				<td>
					<td class="width-50" align="right">
						{{-- @format_currency($last_end_amount)  
						@format_currency($cash_books[0]->sum_amount + $sum_revenue - $sum_expense) 
					</td>
				</td>
			<tr>
		</table>
	</div> --}}
	
@endforeach