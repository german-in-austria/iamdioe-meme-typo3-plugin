$(document).ready(function(){
  $('button.meme-upvote').on('click', (e) => {
    var aThis = $(e.target);
    e.preventDefault();
    e.stopPropagation();
    $('form.memelistform .meme-form-upvote').val(1);
    $('form.memelistform .meme-form-uid').val(aThis.parent().data('memeuid'));
    var aData = $('form.memelistform').serialize();
    $('form.memelistform .meme-form-upvote').val(0);
    $('form.memelistform .meme-form-uid').val(0);
    console.log(aThis, aData);
    $(aThis).attr('disabled', true);
    $.ajax({
      url: $('form.memelistform').attr('action'),
      type: "POST",
      data: aData,
      success: function(response) {
        console.log(response);
        $(aThis).attr('disabled', false);
      },
      error: function (jqXHR, textStatus, errorThrow) {
        alert('Senden hat leider nicht geklappt!');
        console.log('Ajax request - ' + textStatus + ': ' + errorThrow);
        $(aThis).attr('disabled', false);
      }
    })
  })
})
