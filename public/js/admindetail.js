document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    const images = document.querySelectorAll(".preview-image");
    const closeModal = document.querySelector(".close-modal");

    images.forEach((image) => {
        image.addEventListener("click", function () {
            modal.style.display = "flex";
            modalImg.src = this.src;
        });
    });

    function hideModal() {
        modal.style.display = "none";
    }

    closeModal.addEventListener("click", hideModal);

    modal.addEventListener("click", function (e) {
        if (e.target === modal) {
            hideModal();
        }
    });
});
