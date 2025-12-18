<div class="box box-solid @if(!empty($income->type) && $income->type == 'income_refund') hide @endif" id="recur_income_div">
	<div class="box-body">
		<div class="row">
			<div class="col-md-4 col-sm-6">
				<br>
				<label>
	              {!! Form::checkbox('is_recurring', 1, !empty($income->is_recurring) == 1, ['class' => 'input-icheck', 'id' => 'is_recurring']); !!} @lang('lang_v1.is_recurring')?
	            </label>@show_tooltip(__('lang_v1.recurring_income_help'))
			</div>
			<div class="col-md-4 col-sm-6">
		        <div class="form-group">
		        	{!! Form::label('recur_interval', __('lang_v1.recur_interval') . ':*' ) !!}
		        	<div class="input-group">
		               {!! Form::number('recur_interval', !empty($income->recur_interval) ? $income->recur_interval : null, ['class' => 'form-control', 'style' => 'width: 50%; z-index: 0;']); !!}
		               
		                {!! Form::select('recur_interval_type', ['days' => __('lang_v1.days'), 'months' => __('lang_v1.months'), 'years' => __('lang_v1.years')], !empty($income->recur_interval_type) ? $income->recur_interval_type : 'days', ['class' => 'form-control', 'style' => 'width: 50%;z-index: 0;', 'id' => 'recur_interval_type']); !!}
		                
		            </div>
		        </div>
		    </div>

		    <div class="col-md-4 col-sm-6">
		        <div class="form-group">
		        	{!! Form::label('recur_repetitions', __('lang_v1.no_of_repetitions') . ':' ) !!}
		        	{!! Form::number('recur_repetitions', !empty($income->recur_repetitions) ? $income->recur_repetitions : null, ['class' => 'form-control']); !!}
			        <p class="help-block">@lang('lang_v1.recur_income_repetition_help')</p>
		        </div>
		    </div>
		    @php
		    	$repetitions = [];
		    	for ($i=1; $i <= 30; $i++) { 
		    		$repetitions[$i] = str_ordinal($i);
		        }
		    @endphp
		    <div class="recur_repeat_on_div col-md-4 @if(empty($income->recur_interval_type)) hide @elseif(!empty($income->recur_interval_type) && $income->recur_interval_type != 'months') hide @endif">
		        <div class="form-group">
		        	{!! Form::label('subscription_repeat_on', __('lang_v1.repeat_on') . ':' ) !!}
		        	{!! Form::select('subscription_repeat_on', $repetitions, !empty($income->subscription_repeat_on) ? $income->subscription_repeat_on : null, ['class' => 'form-control', 'placeholder' => __('messages.please_select')]); !!}
		        </div>
		    </div>
		</div>
	</div>
</div>