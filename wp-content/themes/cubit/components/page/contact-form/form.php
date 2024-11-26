<div class="form">
    <div class="pb-4 form-row">
        <div class="w-full form-col">
            [text* your-name class:input-class placeholder "Full Name"]
        </div>
        <div class="w-full pb-4 form-col">
            [email* your-email class:input-class placeholder "Email Address"]
        </div>
    </div>
    <div class="pb-4 form-row">
        <div class="w-full form-col">
            [tel* tel-phone class:input-class placeholder "Phone Number"]
        </div>
        <div class="w-full pb-4 form-col">
            [text* address class:input-class placeholder "Business Address"]
        </div>
    </div>
    <div class="pb-4">
        [textarea* rows:2 cols:4 your-message class:input-class placeholder "How can we Help you ?"]
    </div>
    <div class="pb-4">
        [checkbox* interests "Earthworks" "Siteworks" "Development" "Demolition" "Construction" "Bulk Haulage" "Fire
        Management"]
    </div>
    <div class="button">
        [submit class:form-submit-button
        "Submit"]
    </div>
</div>