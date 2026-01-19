@if ($message = Session::get('status'))
    <div class="alert alert-success">{{ $message }}</div>
@endif

<form id="contactForm" method="POST" action="{{ route('contact.submit') }}">
    @csrf

    <div class="form-group">
        <input type="text" class="form-control" name="name" placeholder="Your Name" required>
    </div>

    <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="Your Email" required>
    </div>

    <div class="form-group">
        <input type="tel" class="form-control" name="contact_number" placeholder="Your Contact number">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" name="subject" placeholder="Subject" required>
    </div>

    <div class="form-group">
        <textarea class="form-control" name="message" placeholder="Your Message" rows="5" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Send Message</button>
</form>
