function liveSearch(value) {
  if (!value) {
    document.getElementById("result").innerHTML = "";
    return;
  }
  fetch("ajax_search.php?q=" + encodeURIComponent(value))
    .then((res) => res.text())
    .then((data) => (document.getElementById("result").innerHTML = data))
    .catch((err) => console.error(err));
}

function advancedSearch() {
  const year = document.getElementById("year").value;
  const rating = document.getElementById("rating").value;

  // Build query params
  const params = new URLSearchParams();
  if (year !== "") params.append("year", year);
  if (rating !== "") params.append("rating", rating);

  fetch("ajax_search.php?" + params.toString())
    .then((res) => res.text())
    .then((data) => (document.getElementById("result").innerHTML = data))
    .catch((err) => console.error(err));
}
