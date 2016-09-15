<div class="row">
	<div class="col-xs-12">
		<p class="payment_module">
			<div class="box">
				<div class="row">
					<div class="col-sm-4">
						<h3>{$omise_title}</h3>
						<form>
							<div class="row">
								<div class="form-group col-sm-12">
									<label for="omise_card_number" class="required">&nbsp;{l s='Card number' mod='omise'}</label>
									<input type="text" class="form-control" id="omise_card_number" placeholder="{l s='Card number' mod='omise'}" />
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-12">
									<label for="omise_card_holder_name" class="required">&nbsp;{l s='Card holder name' mod='omise'}</label>
									<input type="text" class="form-control" id="omise_card_holder_name" placeholder="{l s='Card holder name' mod='omise'}" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="omise_card_expiration_month" class="required">&nbsp;{l s='Expiration month' mod='omise'}</label>
										<input type="text" class="form-control" id="omise_card_expiration_month" placeholder="{l s='MM' mod='omise'}" />
									</div>
								</div>
								<div class="col-sm-6 pull-right">
									<div class="form-group">
										<label for="omise_card_expiration_year" class="required">&nbsp;{l s='Expiration year' mod='omise'}</label>
										<input type="text" class="form-control" id="omise_card_expiration_year" placeholder="{l s='YYYY' mod='omise'}" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="omise_card_security_code" class="required">&nbsp;{l s='Security code' mod='omise'}</label>
										<input type="password" class="form-control" id="omise_card_security_code" placeholder="{l s='Security code' mod='omise'}" />
									</div>
								</div>
							</div>
							<button class="button btn btn-default">{l s='Checkout' mod='omise'}</button>
						</form>
					</div>
				</div>
			</div>
		</p>
	</div>
</div>