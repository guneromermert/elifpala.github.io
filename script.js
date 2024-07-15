$(document).ready(function() {
    $('#contact-form').submit(function(e) {
        e.preventDefault(); // Formun normal submit işlemini engelliyoruz
        
        // Form verilerini al
        var formData = {
            'name': $('#name').val(),
            'phone': $('#phone').val(),
            'email': $('#email').val()
        };
        
        // AJAX ile formu gönder
        $.ajax({
            type: 'POST',
            url: 'send_mail.php',
            data: formData,
            dataType: 'json',
            encode: true
        })
        .done(function(data) {
            // Başarılı gönderim durumunda mesajı göster
            $('#form-messages').removeClass('error');
            $('#form-messages').addClass('success');
            $('#form-messages').html('<p>' + data.message + '</p>');
            
            // Formu temizle
            $('#name').val('');
            $('#phone').val('');
            $('#email').val('');
            
            // Mesajı 5 saniye sonra gizle
            setTimeout(function() {
                $('#form-messages').text('');
            }, 5000);
        })
        .fail(function(data) {
            // Hata durumunda mesajı göster
            $('#form-messages').removeClass('success');
            $('#form-messages').addClass('error');
            if (data.responseText !== '') {
                $('#form-messages').html('<p>' + data.responseText + '</p>');
            } else {
                $('#form-messages').html('<p>Bir hata oluştu ve mesaj gönderilemedi.</p>');
            }
        });
    });
});
