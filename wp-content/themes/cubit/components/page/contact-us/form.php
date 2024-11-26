<div class="form">
    <div class="form-row ">
        <div class="form-col">
            <label for="your-name" class="lablename">Your Name <span class="text-orange-400">*</span></label>
            [text* your-name class:input-class placeholder "First Name"]
        </div>

        <div class="form-col ">
            <label for="email" class="lablename">Email <span class="text-orange-400">*</span></label>
            [email* email class:input-class placeholder "Email"]
        </div>
    </div>

    <div class="form-row">
        <div class="form-col ">
            <label for="tel-phone" class="lablename">Telephone <span class="text-orange-400">*</span></label>
            [tel* tel-phone class:input-class placeholder "Phone"]
        </div>

        <div class="form-col">
            <label for="text" class="lablename">Text</label>
            [text* suburb class:input-class placeholder "Suburb"]
        </div>

    </div>

    <div class="form-col">
        <label for="Your Message" class="lablename">Your Message</label>
        [textarea message class:input-class placeholder "Write Your Message Here"]
    </div>

    <!-- <div class="py-4 max-w-52 ">
        [recaptcha]  </div>-->

    <div class="button">
        [submit class:form-submit-button
        "Send Your Message "]
    </div>
</div>