$("#verifyBVN").on("click", function (event) {

    // Auto-detect MIME type from Base64 string
    const getMimeType = (base64) => {
        if (!base64) return 'image/jpeg';
        if (base64.startsWith('iVBORw0KGgo')) return 'image/png';
        if (base64.startsWith('/9j/')) return 'image/jpeg';
        if (base64.startsWith('R0lGOD')) return 'image/gif';
        return 'image/jpeg';
    };

    event.preventDefault();

    let data = new FormData(this.form);
    let validationInfo = document.getElementById("validation-info");
    let download = document.getElementById("download");

    var preloader = $('.page-loading');

    function showLoader() {
        preloader.addClass('active').show();
    }

    function hideLoader() {
        preloader.removeClass('active');
        setTimeout(function () {
            preloader.hide();
        }, 1000);
    }

    $.ajax({
        type: "post",
        url: "/user/bvn-retrieve",
        dataType: "json",
        data,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

            showLoader();
            $("#download").addClass("d-none");

        },
        success: function (result) {
            hideLoader();

            if (result && result.data) {
                const mime = getMimeType(result.data.image);
                const ext = mime.split('/')[1];

                // Unhide the container and clear hidden class
                validationInfo.classList.remove("hidden");
                $(validationInfo).show();

                validationInfo.innerHTML = `
<div class="border border-light p-4 rounded bg-white shadow-sm">
  <div class="row align-items-start">
    <!-- Image Section -->
    <div class="col-12 col-md-4 text-center mb-4 mb-md-0">
      <div class="position-relative d-inline-block p-2 border rounded-3 bg-light">
        <img class="img-fluid rounded shadow-sm" src="data:${mime};base64, ${result.data.image}" alt="User Image" style="max-width: 180px; height: auto;">
        <div class="mt-3">
           <a href="data:${mime};base64, ${result.data.image}" download="BVN_Image_${result.data.idNumber}.${ext}" class="btn btn-sm btn-primary w-100">
              <i class="bi bi-download"></i> Download Photo
           </a>
        </div>
      </div>
    </div>

    <!-- Data Section -->
    <div class="col-12 col-md-8">
      <div class="ps-md-4">
        <div class="row border-bottom py-2 g-0">
          <div class="col-12 col-sm-5 text-muted fw-semibold">BVN Number</div>
          <div class="col-12 col-sm-7 fw-bold text-primary text-sm-end" id="bvnno">${result.data.idNumber}</div>
        </div>
        <div class="row border-bottom py-2 g-0">
          <div class="col-12 col-sm-5 text-muted fw-semibold">First Name</div>
          <div class="col-12 col-sm-7 fw-bold text-sm-end">${result.data.firstName}</div>
        </div>
        <div class="row border-bottom py-2 g-0">
          <div class="col-12 col-sm-5 text-muted fw-semibold">Last Name</div>
          <div class="col-12 col-sm-7 fw-bold text-sm-end">${result.data.lastName}</div>
        </div>
        <div class="row border-bottom py-2 g-0">
          <div class="col-12 col-sm-5 text-muted fw-semibold">Middle Name</div>
          <div class="col-12 col-sm-7 fw-bold text-sm-end">${result.data.middleName ?? '—'}</div>
        </div>
        <div class="row border-bottom py-2 g-0">
          <div class="col-12 col-sm-5 text-muted fw-semibold">Date of Birth</div>
          <div class="col-12 col-sm-7 text-sm-end fw-semibold">${result.data.dateOfBirth}</div>
        </div>
        <div class="row border-bottom py-2 g-0">
          <div class="col-12 col-sm-5 text-muted fw-semibold">Gender</div>
          <div class="col-12 col-sm-7 text-sm-end"><span class="badge bg-light text-dark border">${result.data.gender}</span></div>
        </div>
        <div class="row border-bottom py-2 g-0">
          <div class="col-12 col-sm-5 text-muted fw-semibold">Phone Number</div>
          <div class="col-12 col-sm-7 text-sm-end">${result.data.mobile}</div>
        </div>
        <div class="row border-bottom py-2 g-0">
          <div class="col-12 col-sm-5 text-muted fw-semibold">Enrollment Bank</div>
          <div class="col-12 col-sm-7 text-sm-end">${result.data.enrollmentInstitution ?? result.data.enrollment_bank ?? '—'}</div>
        </div>
        <div class="row border-bottom py-2 g-0">
          <div class="col-12 col-sm-5 text-muted fw-semibold">Enrollment Branch</div>
          <div class="col-12 col-sm-7 text-sm-end text-truncate">${result.data.enrollmentBranch ?? result.data.enrollment_branch ?? '—'}</div>
        </div>
        ${result.data.nin ? `
        <div class="row border-bottom py-2 g-0">
          <div class="col-12 col-sm-5 text-muted fw-semibold">Linked NIN</div>
          <div class="col-12 col-sm-7 text-sm-end fw-bold text-success">${result.data.nin}</div>
        </div>
        ` : ''}
      </div>
    </div>
  </div>
</div>
`;

                $("#download").removeClass("d-none"); // show
            } else {
                 $("#errorMsg").show();
                 $("#message").html("Invalid Response from Server");
                 setTimeout(function () {
                     $("#errorMsg").fadeOut();
                 }, 10000);
            }
        },
        error: function (data) {
            $("#loader").hide();
            $.each(data.responseJSON.errors, function (key, value) {
                $("#errorMsg").show();
                $("#message").html(value);
            });
            setTimeout(function () {
                $("#errorMsg").hide();
            }, 5000);
        },
    });
});

$("#freeSlip").on("click", function (event) {
    let getBVN = $("#bvnno").html();
    $.ajax({
        type: "get",
        url: "/user/standardBVN/" + getBVN,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            if (response.view) {
                var newWindow = window.open("", "_blank");
                newWindow.document.write(response.view);
                newWindow.document.close();
            } else {
                console.error("No view content received");
            }
        },
        error: function (data) {
            $.each(data.responseJSON.errors, function (key, value) {
                $("#errorMsg2").show();
                $("#message2").html(value);
            });
            setTimeout(function () {
                $("#errorMsg2").hide();
            }, 5000);
        },
    });
});

$("#paidSlip").on("click", function (event) {
    let getBVN = $("#bvnno").html();
    $.ajax({
        type: "get",
        url: "/user/premiumBVN/" + getBVN,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            if (response.view) {
                var newWindow = window.open("", "_blank");
                newWindow.document.write(response.view);
                newWindow.document.close();
            } else {
                console.error("No view content received");
            }
        },
        error: function (data) {
            $.each(data.responseJSON.errors, function (key, value) {
                $("#errorMsg2").show();
                $("#message2").html(value);
            });
            setTimeout(function () {
                $("#errorMsg2").hide();
            }, 5000);
        },
    });
});
$("#plasticSlip").on("click", function (event) {
    let getBVN = $("#bvnno").html();

    fetch("/user/plasticBVN/" + getBVN, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((response) => {
            if (response.ok) {
                // Extract filename from Content-Disposition header
                const contentDisposition = response.headers.get(
                    "Content-Disposition"
                );
                let filename = "document.pdf"; // Default filename if not found in headers
                if (
                    contentDisposition &&
                    contentDisposition.indexOf("attachment") !== -1
                ) {
                    const filenameRegex =
                        /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(contentDisposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, "");
                    }
                }
                return response.blob().then((blob) => ({ blob, filename }));
            } else {
                return response.json().then((data) => {
                    // Handle errors
                    $.each(data.errors, function (key, value) {
                        $("#errorMsg2").show();
                        $("#message2").html(value);
                    });
                    setTimeout(function () {
                        $("#errorMsg2").hide();
                    }, 5000);
                });
            }
        })
        .then(({ blob, filename }) => {
            if (blob) {
                // Create a link element, use it to download the blob with the extracted filename
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = filename; // Use the extracted filename
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            // Handle errors
            $.each(data.errors, function (key, value) {
                $("#errorMsg2").show();
                $("#message2").html(value);
            });
            setTimeout(function () {
                $("#errorMsg2").hide();
            }, 5000);
        });
});
