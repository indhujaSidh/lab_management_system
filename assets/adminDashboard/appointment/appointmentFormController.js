// setup an add button
var $addTagButton = $('<a title="add new test mt-2" class="contact-button add-new-test ms-3">\n' +
    '                            <i class="fa fas fa-plus contact-button__icon"></i>\n' +
    '                        </a>');
var $newLinkLi = $('<li></li>').append($addTagButton);

// Get the ul that holds the collection of brands
$collectionHolder = $('ul.tests');
$collectionHolder.data('index', $collectionHolder.find(':input').length);
$addTagButton.on('click', function (e) {
    addTagForm($collectionHolder, $newLinkLi);
});
$collectionHolder.find('li').each(function () {
    addTagFormDeleteLink($(this));
});
$collectionHolder.append($newLinkLi);
function addTagForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<div class="col col-md-2">\n' +
        '                            <button class="btn btn-danger remove-code" data-index="{{ loop.index }}"><i class="bi bi-trash2"></i></button>\n' +
        '                        </div>');
    $tagFormLi.find('.testsDelete').append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}


$(document).ready(function () {
    $('.patient-select').select2({
        minimumInputLength: 4, // Minimum characters required before triggering the AJAX request
        ajax: {
            url: ajax_auto_complete_user_contacts,
            type: 'GET',
            dataType: 'json',
            delay: 100,
            data: function (params) {
                return {
                    input: params.term,
                    limit: 20 // Limit the number of results to 20
                };
            },
            processResults: function (data) {
                console.log(data);
                var results = [];
                $.each(data.primary, function (index, user) {
                    results.push({
                        id: user.id,
                        text: user.contact,
                    });
                });
                console.log(results);
                return {
                    results: results
                };
            },
            cache: true
        }
    });

});
