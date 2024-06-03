document.getElementById("products-btn").addEventListener("click", function() {
    document.getElementById("popular-section").scrollIntoView({ behavior: "smooth" });
});

function toggleDropdown() {
    var dropdownContent = document.getElementById("dropdownContent");
    if (dropdownContent.style.display === "block") {
        dropdownContent.style.display = "none";
    } else {
        dropdownContent.style.display = "block";
    }
}
