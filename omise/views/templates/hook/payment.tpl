<div class="row">
	<div class="col-xs-12">
		<p class="payment_module">
			<div class="box">
        		<div class="row">
        			<h3>Omise Payment Gateway</h3>
        			<div class="col-sm-3">
        				<form>
        					<div class="row">
        						<div class="form-group col-sm-12">
        							<label for="omise_card_number">{l s='Card number' mod='omise'}</label>
        							<input type="text" class="form-control" id="omise_card_number" placeholder="{l s='Card number' mod='omise'}" />
        						</div>
        					</div>
        					<div class="row">
        						<div class="form-group col-sm-12">
        							<label for="omise_card_holder_name">{l s='Card holder name' mod='omise'}</label>
        							<input type="text" class="form-control" id="omise_card_holder_name" placeholder="{l s='Card holder name' mod='omise'}" />
        						</div>
        					</div>
        					<div class="row">
        						<div class="col-sm-6">
        							<div class="form-group">
        								<label for="omise_card_expiration_month">{l s='Expiration month' mod='omise'}</label>
        								<input type="text" class="form-control" id="omise_card_expiration_month" placeholder="{l s='MM' mod='omise'}" />
        							</div>
        						</div>
        						<div class="col-sm-6 pull-right">
        							<div class="form-group">
        								<label for="omise_card_expiration_year">{l s='Expiration year' mod='omise'}</label>
        								<input type="text" class="form-control" id="omise_card_expiration_year" placeholder="{l s='YYYY' mod='omise'}" />
        							</div>
        						</div>
        					</div>
        					<div class="row">
        						<div class="col-sm-4">
        							<div class="form-group">
        								<label for="security_code">{l s='CVC' mod='omise'}</label>
        								<input type="password" class="form-control" id="security_code" placeholder="{l s='CVC' mod='omise'}" />
        							</div>
        						</div>
        					</div>
        					<button type="submit" class="button btn btn-default">{l s='Checkout' mod='omise'}</button>
        				</form>
        			</div>
        		</div>
    		</div>
		</p>
	</div>
</div>