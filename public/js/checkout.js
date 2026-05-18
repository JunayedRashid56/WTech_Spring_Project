const checkoutForm = document.getElementById("checkoutForm");

if (checkoutForm) {
   checkoutForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(checkoutForm);

      fetch("/WTech Project/api/checkout/place_order.php", {
         method: "POST",
         body: formData,
      })
         .then((response) => response.json())
         .then((data) => {
            if (data.success) {
               window.location.href = "/WTech Project/views/checkout/confirmation.php";
            } else {
               alert(data.message);
            }
         })
         .catch((error) => {
            console.log(error);
         });
   });
}
