@layout('views/layouts/master')
@section('css')
<link rel="stylesheet" href="<?= base_url($frontendThemePath .'assets/css/expanded/contact.css') ?>">
@endsection
@section('content')
    <section class="contact-part">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <h1 class="section-page-title">Contact</h1>
                    <p class="contact-describe">{{ htmlspecialchars_decode($page->content ?? '') }}</p>
                    <div class="contact-content">
                        <ul>
                            <li>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 2C15.31 2 18 4.66 18 7.95C18 12.41 12 19 12 19C12 19 6 12.41 6 7.95C6 4.66 8.69 2 12 2ZM12 6C11.4696 6 10.9609 6.21071 10.5858 6.58579C10.2107 6.96086 10 7.46957 10 8C10 8.53043 10.2107 9.03914 10.5858 9.41421C10.9609 9.78929 11.4696 10 12 10C12.5304 10 13.0391 9.78929 13.4142 9.41421C13.7893 9.03914 14 8.53043 14 8C14 7.46957 13.7893 6.96086 13.4142 6.58579C13.0391 6.21071 12.5304 6 12 6ZM20 19C20 21.21 16.42 23 12 23C7.58 23 4 21.21 4 19C4 17.71 5.22 16.56 7.11 15.83L7.75 16.74C6.67 17.19 6 17.81 6 18.5C6 19.88 8.69 21 12 21C15.31 21 18 19.88 18 18.5C18 17.81 17.33 17.19 16.25 16.74L16.89 15.83C18.78 16.56 20 17.71 20 19Z"
                                        fill="#FA5E01"></path>
                                </svg>
                                <span>{{ frontendData::get_backend('address') }} </span>
                            </li>
                            <li>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15 12H17C17 10.6739 16.4732 9.40215 15.5355 8.46447C14.5979 7.52678 13.3261 7 12 7V9C12.7956 9 13.5587 9.31607 14.1213 9.87868C14.6839 10.4413 15 11.2044 15 12ZM19 12H21C21 7 16.97 3 12 3V5C15.86 5 19 8.13 19 12ZM20 15.5C18.75 15.5 17.55 15.3 16.43 14.93C16.08 14.82 15.69 14.9 15.41 15.18L13.21 17.38C10.38 15.94 8.06 13.62 6.62 10.79L8.82 8.59C9.1 8.31 9.18 7.92 9.07 7.57C8.7 6.45 8.5 5.25 8.5 4C8.5 3.73478 8.39464 3.48043 8.20711 3.29289C8.01957 3.10536 7.76522 3 7.5 3H4C3.73478 3 3.48043 3.10536 3.29289 3.29289C3.10536 3.48043 3 3.73478 3 4C3 8.50868 4.79107 12.8327 7.97918 16.0208C11.1673 19.2089 15.4913 21 20 21C20.2652 21 20.5196 20.8946 20.7071 20.7071C20.8946 20.5196 21 20.2652 21 20V16.5C21 16.2348 20.8946 15.9804 20.7071 15.7929C20.5196 15.6054 20.2652 15.5 20 15.5Z"
                                        fill="#FA5E01"></path>
                                </svg>
                                <span>{{ frontendData::get_backend('phone') }}</span>
                            </li>
                            <li>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M20 8L12 13L4 8V6L12 11L20 6V8ZM20 4H4C2.89 4 2 4.89 2 6V18C2 18.5304 2.21071 19.0391 2.58579 19.4142C2.96086 19.7893 3.46957 20 4 20H20C20.5304 20 21.0391 19.7893 21.4142 19.4142C21.7893 19.0391 22 18.5304 22 18V6C22 4.89 21.1 4 20 4Z"
                                        fill="#FA5E01"></path>
                                </svg>
                                <span>{{ frontendData::get_backend('email') }} </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-lg-6">  
                    <iframe class="contact-map" src="{{ frontendData::get_frontend('embed_map') }}"  style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>
    <section class="message-part">
        <div class="container">
            <div class="message-content">
                <h2 class="section-title">Message Us</h2>
                <form id="contact_form">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Your Email</label>
                                <input type="email" name="email" class="form-control" id="email">
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="sub">
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="message" class="form-label">Message</label>
                                <textarea name="message" class="form-control" id="message"></textarea>

                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <button name="msg_btn" id="send_email_btn" type="button" class="btn btn-inline mt-3">Send
                                Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
       $(document).ready(function(){
            // if ($('iframe').length > 0) {
             //   $('iframe').addClass('contact-map');
            // }
        });
    </script>
    <script>
        $(document).on('click', '#send_email_btn', function() {
            var error = false;
            var name = $('#name').val();
            var email = $('#email').val();
            var subject = $('#subject').val();
            var message = $('#message').val();
            $('#name, #email, #subject, #message').css("border-color", '');
            if (name.trim() == '') {
                error = true;
                $('#name').css("border-color", 'red');
            }
            if (email.trim() == '') {
                error = true;
                $('#email').css("border-color", 'red');
            } else if (!check_email(email)) {
                error = true;
                $('#email').css("border-color", 'red');
                toastrAlert('error', 'Please enter a valid email address.')
            }
            if (subject.trim() == '') {
                error = true;
                $('#subject').css("border-color", 'red');
            }
            if (message.trim() == '') {
                error = true;
                $('#message').css("border-color", 'red');
            }
            if (!error) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('frontend/contactMailSend') ?>",
                    data: {
                        'name': name,
                        'email': email,
                        'subject': subject,
                        'message': message
                    },
                    dataType: "html",
                    success: function(data) {
                        if (data === 'success') {
                            toastrAlert('success', 'Email Send Successfull')
                            $('#name').val('');
                            $('#email').val('');
                            $('#subject').val('');
                            $('#message').val('');

                        } else {
                            toastrAlert('error', 'Failed to send email. Please try again later.')
                        }
                    },
                    error: function() {
                        toastrAlert('error', 'Failed to send email. Please try again later.')
                    }
                });
            }
        });

        function check_email(email) {
            var status = false;
            var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
            if (email.search(emailRegEx) == -1) {
                $("#to_error").html('');
                $("#to_error").html("<?= $this->lang->line('mail_valid') ?>").css("text-align", "left").css("color", 'red');
            } else {
                status = true;
            }
            return status;
        }

        function toastrAlert(type, value) {
            var type = type === 'success' ? 'success' : 'error';
            toastr[type](value)
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "500",
                "hideDuration": "500",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }
    </script>
@endsection
