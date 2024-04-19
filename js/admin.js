function generate_uid (){
    return Date.now().toString(36) + Math.random().toString(36).substr(2);
}
$(document).on('click', '.add_option', function () {
    let elem = $(this).siblings('.options_group');
    let uid = $(this).parent().parent().data('uid');
    elem.append(`
        <div class="input-group option mb-3">
           <input type="text" class="form-control" name="question[`+uid+`][option][`+generate_uid() +`]" placeholder="Enter option text" aria-label="Recipient's username" aria-describedby="button-addon2">
           <button class="btn btn-outline-secondary delete_option" type="button">Delete</button>
        </div>   `
    );
});
$(document).on('click', '.delete_option', function () {
    let uid = $(this).parent().find('input').data('uid');
    $.ajax({
        type:'POST',
        url:'functions.php',
        data:{functionname:'delete_option', id:uid},
        success: function (response) {
            console.log(response);
        }
    });
    $(this).parent().remove();

});

$(document).on('change', '.multiple_checkbox', function () {
    let uid = $(this).parent().parent().data('uid');
    if (!this.checked) {
        $(this).parent().parent().find('.options_container').empty();
    } else {
        $(this).parent().parent().find('.options_container').html(
            `
           <div class="options_group">
            <div class="input-group option mb-3">
                <input type="text" class="form-control" placeholder="Enter option text"
                aria-label="Recipient's username" aria-describedby="button-addon2" name="question[`+uid+`][option][`+generate_uid() +`]">
               <button class="btn btn-outline-secondary delete_option" type="button">Delete</button>
              </div>
            </div>
        <button type="button" class="btn btn-primary mt-3 add_option" >Add Option</button>
          `
        );
    }
});

$('#add_question').on('click', function () {
    var uid = generate_uid();
    $('#survey_questions').append(
        ` <div class="question_container question" data-uid="`+uid+`">
                            <h3 class="mt-3 mb-3 question_title">Question&nbsp;</h3>
                            <button type="button" class="btn btn-danger mt-3 mb-3 delete_question">Delete question</button>
                            <div class="form-group">
                                <label for="surveyTitle">Question Title:</label>
                                <input type="text" class="form-control" id="surveyTitle" name="question[`+uid+`][title]" required>
                            </div>
                            <div class="form-check mt-3">
                                <input type='hidden' value='0' name='question[`+uid+`][is_multiple_choice]'>
                                <input class="form-check-input multiple_checkbox" type="checkbox" value="1" id="flexCheckIndeterminate"
                                       checked="true"   name="question[`+uid+`][is_multiple_choice]">
                                <label id="use_multiple_choice"  class="form-check-label" for="flexCheckIndeterminate">
                                    Use multiple choices question
                                </label>
                            </div>
                            <div class="form-group mt-3 options_container">
                                <div class="options_group">
                                    <div class="input-group option mb-3">
                                        <input type="text" class="form-control" placeholder="Enter option text" required
                                               aria-label="Recipient's username" aria-describedby="button-addon2" name="question[`+uid+`][option][`+generate_uid() +`]">
                                        <button class="btn btn-outline-secondary delete_option" type="button">Delete</button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary mt-3 add_option">Add Option</button>
                            </div>
                            <hr>

                        </div>
    `
    );
});

$(document).on('click','.delete_question',function (){
    let uid = $(this).parent().data('uid');
    $.ajax({
        type:'POST',
        url:'functions.php',
        data:{functionname:'delete_question', id:uid},
        success: function (response) {
            console.log(response);
        }
    });
    $(this).parent().remove();
});

$(document).on('click','.delete_question',function (){
    let uid = $(this).parent().data('uid');
    $.ajax({
        type:'POST',
        url:'functions.php',
        data:{functionname:'delete_question', id:uid},
        success: function (response) {
            console.log(response);
        }
    });
    $(this).parent().remove();
});

$('.copy_button').on('click',function (){
    let id = $(this).data('uid');
    navigator.clipboard.writeText(document.location.origin + '/quiz.php?quiz='+id);
});

$('.copy_button_category').on('click',function () {
    let id = $(this).data('uid');
    navigator.clipboard.writeText(document.location.origin + '?category='+id);
})