const checkoutForm = document.getElementById("checkoutForm");

if (checkoutForm) {
   checkoutForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(checkoutForm);

      fetch("../../api/checkout/place-order.php", {
         method: "POST",
         body: formData,
      })
         .then((response) => response.json())
         .then((data) => {
            if (data.success) {
               window.location.href = "../../views/checkout/confirmation.php";
            } else {
               alert(data.message);
            }
         })
         .catch((error) => {
            console.log(error);
         });
   });
}
