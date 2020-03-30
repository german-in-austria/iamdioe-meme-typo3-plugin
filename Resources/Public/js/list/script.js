$(document).ready(function(){
  updateVoteStates();
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
        $('.meme-votes-uid-' + aThis.parent().data('memeuid')).text(parseInt($('.meme-votes-uid-' + aThis.parent().data('memeuid')).first().text()) + 1);
        addVoteState(parseInt(aThis.parent().data('memeuid')));
        updateVoteStates();
      },
      error: function (jqXHR, textStatus, errorThrow) {
        alert('Senden hat leider nicht geklappt!');
        console.log('Ajax request - ' + textStatus + ': ' + errorThrow);
        $(aThis).attr('disabled', false);
      }
    })
  })
})

function updateVoteStates() {
  var votes = JSON.parse(localStorage.getItem('iamdioememe'));
  if (votes) {
    $('button.meme-upvote').each(function () {
      if (votes.indexOf(parseInt($(this).parent().data('memeuid'))) > -1) {
        $(this).attr('disabled', true);
        $(this).find('span.glyphicon').removeClass('glyphicon-thumbs-up').addClass('glyphicon-ok');
      }
    });
  }
}

function addVoteState(uid) {
  var votes = JSON.parse(localStorage.getItem('iamdioememe'));
  if(!votes) {
    votes = [];
  }
  votes.push(uid);
  localStorage.setItem('iamdioememe', JSON.stringify(votes));
}
