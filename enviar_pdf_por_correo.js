document.addEventListener('DOMContentLoaded', function() {
    // Tu código JavaScript aquí
    document.getElementById('emailForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        var formData = new FormData(this);
      
        fetch('enviar_pdf_por_correo.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (response.ok) {
            alert('Correo enviado correctamente.');
          } else {
            alert('Error al enviar el correo.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    });
});
