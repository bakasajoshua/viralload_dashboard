@extends('layouts.master')

@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			    <strong>Fill in the contact form below</strong>
			</div>
			<div class="panel-body" id="contact_us">
				<center><div id="error_div"></div></center>
			    <form class="form-horizontal" role="form" method="post" action="{{ route('contact-us') }}">
					<div class="form-group">
						<label for="name" class="col-sm-2 control-label" style="color: black;">Name:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="name" name="name" placeholder="First & Last Name" required>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label" style="color: black;">Email:</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" required>
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-2 control-label" style="color: black;">Phone:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="phone" name="phone" placeholder="0722000111" required>
						</div>
					</div>
					<div class="form-group">
						<label for="subject" class="col-sm-2 control-label" style="color: black;">Subject:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
						</div>
					</div>
					<div class="form-group">
						<label for="message" class="col-sm-2 control-label" style="color: black;">Message:</label>
						<div class="col-sm-10">
							<textarea class="form-control" rows="4" id="message" name="message" required></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-4 col-sm-offset-2">
							<input id="submit" name="submit" type="submit" value="Submit" class="btn btn-primary" style="color:white; background-color:#1BA39C;">
						</div>
						<div class="col-sm-6" id="loading"></div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
						</div>
					</div>
					<div class="g-recaptcha" data-sitekey="6LcQyDkUAAAAAB6Qx3q3aT1768kpNQ7EGkok-pUj"></div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

<script type="text/javascript">
$().ready(function () {
		$("#email").focusout(function(){
			let mail = $(this).val();
			
			$.get("{{ url('contact-us') }}/"+mail, function(data){
				if (data==false) {
					$("#error_div").html("<div style='color:red;'>Enter a valid email!</div>");
					$("#submit").attr("disabled","true");
				} else {
					$("#error_div").html("");
					$("#submit").removeAttr("disabled");
				}
			});

			$("form").submit(function(event){
				event.preventDefault();
				//Values from the form received
				name 	= $("#name").val();
				email 	= $("#email").val();
				phone 	= $("#phone").val();
				subject = $("#subject").val();
				message = $("#message").val();
				//Setting the loader
				$("#loading").html("<div class='loader'></div>");
				//Posting the contact details provided
				var posting = $.post( "{{ route('contact-us') }}",
								{
									cname	: name,
									cemail	: email,
									cphone	: phone,
									csubject: subject,
									cmessage: message 
								});
				posting.done(function( data ) {
					if (data==0) {
						// Error occured the email was not sent
						setTimeout(function(){
						  $("#loading").html("<div style='color:red;' style='height:36px;'>Error occured, the email was not sent!</div>");
						}, 2000);
						// console.log("Error occured, the email was not sent");
					} else {
						// Email sent very well
						setTimeout(function(){
						  $("#loading").html("<div style='color:green;' style='height:36px;'>Email sent successfully!</div>");
						}, 2000);
						// console.log("Email sent successfully");
					}
				});
			});
		});
	});
</script>

@endsection