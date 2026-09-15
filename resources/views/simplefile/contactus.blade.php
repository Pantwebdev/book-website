@include('userheader')
<section class="contact-map">

    <iframe
        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3505.7312184585967!2d77.1957552754966!3d28.517733375727595!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d1f9d43211e81%3A0x35601d0d4e58183e!2sHind%20Photostat%20Book%20Center!5e0!3m2!1sen!2sin!4v1773480963095!5m2!1sen!2sin"
        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>

    <div class="container cont-us">
        <div class="row">

            <!-- Left Content -->
            <div class="col-md-6 ">
                <div class="left-cont">
                    <h2 class="contact-title">CONTACT US</h2>

                    <p class="contact-text">
                        If you have any questions, feedback, or need assistance, feel free to get in touch with us. Our
                        team is always ready to help you with your queries and provide the best support possible. Simply
                        fill out the contact form or reach us through the provided contact details, and we will respond
                        to you as soon as possible.
                    </p>


                </div>
            </div>

            <!-- Right Content -->
            <div class="col-md-6 right-content">
                <div class="right-con">
                    <p><i class="bi bi-house" style="padding-right:10px;font-size: 22px;"></i><strong> Head
                            Office:</strong> {{ $setting->address ?? '' }}</p>


                    <p><i class="bi bi-envelope" style="padding-right:10px;font-size: 22px;"></i><strong>Email:</strong>
                        {{ $setting->email ?? '' }}<br>

                    </p>


                    <p><i class="bi bi-phone-fill"
                            style="padding-right:10px ;font-size: 22px;"></i><strong>Phone</strong>
                        {{ $setting->phone ?? '' }}</p>


                    <p><i class="bi bi-alarm" style="padding-right:10px ;font-size: 22px;"></i><strong>Timing:</strong>
                        9:00 AM - 10:00 PM (Mon - sun)</p>

                    <div class="d-flex justify-content-between">
                        <p><strong>Connect to</strong></p>
                        <div class="social-icons">
                            <a href="{{ $setting->facebook }}"><i class="bi bi-facebook"></i></a>
                            <a href="{{ $setting->instagram }}"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="contantushome py-5">
    <div class="container">
        <div class="row">
            <!-- RIGHT SIDE : CONTACT INFO -->
            <div class="col-lg-5  ">
                <img src="{{url('userassets/image/contact-us.png')}}" class="img-fluid" alt="contact-us-image">
                <div class="imag-cont-bac">

                </div>
            </div>
            <div class="col-lg-1 ">

            </div>
            <!-- LEFT SIDE : FORM -->
            <div class="col-lg-6 pe-lg-6 mb-5">
                <div class="contact-bor">
                    <h3>
                        SEND US AN EMAIL</h3>
                    <div id="successMessage"></div>

                    <form id="contactForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name*</label>
                            <input type="text" name="name" class="form-control" placeholder="Your Name">
                            <span class="text-danger error-name"></span>
                        </div>


                        <div class=" mb-3">
                            <label class="form-label">Email*</label>
                            <input type="email" name="email" class="form-control" placeholder="Email Address">
                            <span class="text-danger error-email"></span>
                        </div>

                        <div class=" mb-3">
                            <label class="form-label">Phone No*</label>
                            <input type="text" name="Phone" class="form-control" placeholder="Phone Number">
                            <span class="text-danger error-Phone"></span>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Message*</label>
                            <textarea class="form-control" name="message" rows="6"
                                placeholder="Type your message..."></textarea>
                        </div>

                        <button type="submit" class="btn px-4 btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



@include('userfooter')

<script>
$('#contactForm').on('submit', function(e) {
    e.preventDefault();

    $('.text-danger').text('');

    $.ajax({
        url: "{{ route('contact.submit') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {


                $('#contactForm')[0].reset();


                $('#successMessage').html(
                    '<div class="alert alert-success">' + response.message + '</div>'
                );


                setTimeout(function() {
                    $('#successMessage').html('');
                }, 300);
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;

                $.each(errors, function(field, messages) {
                    $('.error-' + field).text(messages[0]);
                });
            }
        }
    });
});
</script>