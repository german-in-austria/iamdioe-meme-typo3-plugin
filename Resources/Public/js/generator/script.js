function getTemplate(name) {
  var h = document.querySelector('.'+ name +'-template').innerHTML
  document.querySelector('.'+ name +'-template').innerHTML = ''
  return h
}

function getFormValues() {
  return $('form.personal').serializeArray()
}

$.fn.memeGenerator("i18n", "de", {
  topTextPlaceholder: "TEXT OBEN",
  bottomTextPlaceholder: "TEXT UNTEN",
  addTextbox: "Textfeld hinzufügen",
})

$(document).ready(function(){
  $("#meme").memeGenerator({
    // useBootstrap: true,
    layout: 'horizontal',
    defaultTextStyle: {
      color: "#FFFFFF",
      size: 80,
      lineHeight: 1.2,
      font: "Impact, Arial",
      style: "normal",
      forceUppercase: true,
      borderColor: "#000000",
      borderWidth: 4,
    },
    showAdvancedSettings: false,
    captions: [
      'Text oben',
      'Text unten'
    ],
    onInit: () => {
      $("#save").click(function(e){
        e.preventDefault()
        e.stopPropagation()
        var form = document.querySelector('form.personal')

        if (form.checkValidity() === false) {
          console.log('invalid')
        } else {
          console.log('valid')
          var imageDataUrl = $("#meme").memeGenerator("save")
          $("#meme").memeGenerator("download", "image.png");
          $.ajax({
            url: "/sendmail",
            type: "POST",
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify({
              image: imageDataUrl,
              personal: getFormValues()
            }),
            dataType: "json",
            success: function(response){
              
            }
          })
        }
        form.classList.add('was-validated')
      })
    }
  })
  $('.images img').on('click', (e) => {
    function toDataURL(url, callback) {
      var xhr = new XMLHttpRequest();
      xhr.onload = function() {
        var reader = new FileReader();
        reader.onloadend = function() {
          callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
      };
      xhr.open('GET', url);
      xhr.responseType = 'blob';
      xhr.send();
    }
    $('img.active').removeClass('active')
    $(e.target).addClass('active')
    // toDataURL(e.target.src, function(dataUrl) {
    //   $('#meme').attr('src', dataUrl)
    // })
    $('#meme').attr('src', e.target.src)
  })
})