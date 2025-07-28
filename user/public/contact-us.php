<?php

	require_once('../include/header.php');
	require_once('../include/navbar.php');

?>


			<div role="main" class="main">

				<section class="page-header page-header-modern bg-color-grey page-header-lg">
					<div class="container">
						<div class="row">
							<div class="col-md-12 align-self-center p-static order-2 text-center">
								<h1 class="font-weight-bold text-dark">Contact Us</h1>
							</div>
							<div class="col-md-12 align-self-center order-1">
								<ul class="breadcrumb d-block text-center">
									<li><a href="#">Home</a></li>
									<li class="active">Contact Us</li>
								</ul>
							</div>
						</div>
					</div>
				</section>

				<div class="container py-4">

					<div class="row mb-2">
						<div class="col">

							<h2 class="font-weight-bold text-7 mt-2 mb-0">Contact Us</h2>
							<p class="mb-4">Feel free to ask for details, don't save any questions!</p>

							<form class="contact-form-recaptcha-v3" action="../private/contact-us-form.php" method="POST">

								<?php

									if (isset($_SESSION['status']) && $_SESSION['status'] == "Success") {
										
								?>

									<div class="alert alert-success" role="alert">
									Email sent successfully! We will get back to you as soon as possible.
									</div>

								<?php

									} elseif (isset($_SESSION['status']) && $_SESSION['status'] == "Failed") {

								?>
										
								<div class="alert alert-danger" role="alert">
									There was an error sending your email. Please try again later.
								</div>

								<?php

									}

									unset($_SESSION['status']);

									

								?>

								<div class="row">
									<div class="form-group col-lg-6">
										<label class="form-label mb-1 text-2">Full Name</label>
										<input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control text-3 h-auto py-2" name="name" required>
									</div>
									<div class="form-group col-lg-6">
										<label class="form-label mb-1 text-2">Email Address</label>
										<input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control text-3 h-auto py-2" name="email" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col">
										<label class="form-label mb-1 text-2">Subject</label>
										<input type="text" value="" data-msg-required="Please enter the subject." maxlength="100" class="form-control text-3 h-auto py-2" name="subject" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col">
										<label class="form-label mb-1 text-2">Message</label>
										<textarea maxlength="5000" data-msg-required="Please enter your message." rows="5" class="form-control text-3 h-auto py-2" name="message" required></textarea>
									</div>
								</div>
								<div class="row">
									<div class="form-group col">
										<input type="submit" value="Send Message" class="btn btn-primary btn-modern" data-loading-text="Loading...">
									</div>
								</div>
							</form>

						</div>
					</div>
					<div class="row mb-5">
						<div class="col-lg-4">

							<div class="overflow-hidden mb-3">
								<h4 class="pt-5 mb-0 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="200" data-plugin-options="{'accY': -200}">Get in <strong>Touch</strong></h4>
							</div>
							<div class="overflow-hidden mb-3">
								<p class="lead text-4 mb-0 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="400" data-plugin-options="{'accY': -200}">If no one listens to you, Berans Trading will</p>
							</div>
							<div class="overflow-hidden">
								<p class="mb-0 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="600" data-plugin-options="{'accY': -200}">Have a question, need a quote, or looking for a specific item? We're here to help. Reach out to us via WhatsApp, email, or social media — and let Berans Trading supply what your business needs to grow.</p>
							</div>

						</div>
						<div class="col-lg-4 offset-lg-1 appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="800" data-plugin-options="{'accY': -200}">

							<h4 class="pt-5">Our <strong>Office</strong></h4>
							<ul class="list list-icons list-icons-style-3 mt-2">
								<li><i class="fas fa-map-marker-alt top-6"></i> <strong>Address:</strong> <?php echo COMPANY_ADDRESS; ?></li>
								<li><i class="fas fa-phone top-6"></i> <strong>Phone:</strong> (+60) <?php echo COMPANY_CONTACT; ?></li>
								<li><i class="fas fa-envelope top-6"></i> <strong>Email:</strong> <a href="mailto:<?php echo COMPANY_EMAIL; ?>"><?php echo COMPANY_EMAIL; ?></a></li>
							</ul>

						</div>
						<div class="col-lg-3 appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="1000" data-plugin-options="{'accY': -200}">

							<h4 class="pt-5">Business <strong>Hours</strong></h4>
							<ul class="list list-icons list-dark mt-2">
								<li><i class="far fa-clock top-6"></i> Monday - Friday - 9am to 5pm</li>
								<li><i class="far fa-clock top-6"></i> Saturday - 9am to 2pm</li>
								<li><i class="far fa-clock top-6"></i> Sunday - Closed</li>
							</ul>

						</div>
					</div>

				</div>



			</div>

<?php

	require_once('../include/footer.php');

?>

<script>
document.getElementById("contactForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch("contact.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("responseMessage").innerHTML = data;
        form.reset();
    })
    .catch(error => {
        document.getElementById("responseMessage").innerHTML = "Something went wrong.";
        console.error(error);
    });
});
</script>

