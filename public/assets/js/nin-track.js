$("#verifyNIN").on("click", function (event) {
    
    // Auto-detect MIME type from Base64 string
    const getMimeType = (base64) => {
        if (!base64) return 'image/jpeg';
        if (base64.startsWith('iVBORw0KGgo')) return 'image/png';
        if (base64.startsWith('/9j/')) return 'image/jpeg';
        if (base64.startsWith('R0lGOD')) return 'image/gif';
        return 'image/jpeg';
    };

    // Stop the button from submitting the form:
    event.preventDefault();

    let data = new FormData(this.form);
    let validationInfo = document.getElementById("validation-info");
    let download = document.getElementById("download");
    $("#errorMsg").hide();

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
        url: "/user/nin-track-retrieve",
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
            $("#loader").hide();

            if (result && result.data) {
//                 validationInfo.innerHTML = `
//             <div class="border border-light">
//    <div class="table-responsive">
//    <center><span class="text-danger mt-4" style="margin-top:5px; padding-top:3px;">${result.data.message}</span></center>
//       <table class="table">
//          <thead >
//             <tr>
//                <th style="border: none ! important;" width="20%"></th>
//                <th style="border: none ! important;"></th>
//                <th style="border: none ! important;"></th>
//                <th style="border: none ! important;"></th>
//             </tr>
//          </thead>
//          <tbody>
//             <tr>
//                <th scope="row" rowspan="9">
//                   <img class="rounded" src="data:image/;base64, ${result.data.face}" alt="User Image" style="width: 250px; height: 250px;">
//                </th>
//             </tr>
//               <tr>
//                <th scope="row" style="text-align:right; border: none ! important;">Tracking Number</th>
//                <td  style="text-align:left">${result.data.trackingid}
//                </td>
//             </tr>
//             <tr>
//                <th scope="row" style="text-align:right; border: none ! important;">NIN</th>
//                <td style="text-align:left" ><span id="nin_no" >${result.data.nin}</span>
//                </td>
//             </tr>
//             <tr>
//                <th scope="row" style="text-align:right; border: none ! important;">FirstName</th>
//                <td  style="text-align:left">${result.data.firstname}
//                </td>
//             </tr>
//             <tr>
//                <th scope="row" style="text-align:right; border: none ! important;">Surname</th>
//                <td  style="text-align:left">${result.data.lastname}
//                </td>
//             </tr>
//             <tr>
//                <th scope="row" style="text-align:right; border: none ! important;">Middle Name</th>
//                <td  style="text-align:left">${result.data.middlename}
//                </td>
//             </tr>
//             <tr>
//                 <th scope="row" style="text-align:right; border: none !important;">Gender</th>
//                 <td style="text-align:left">
//                     ${result.data.gender === 'm' ? 'Male' : result.data.gender === 'f' ? 'Female' : 'Not Specified'}
//                 </td>
//             </tr>
                const mime = getMimeType(result.data.face);
                const ext = mime.split('/')[1];

validationInfo.innerHTML = `
<div class="border border-light p-3">
  <div class="row">
    <div class="col-md-4 text-center mb-3">
      <div class="position-relative d-inline-block">
        <img class="rounded img-fluid shadow-sm border" src="data:${mime};base64, ${result.data.face}" alt="User Image" style="max-width: 200px; height: auto;">
        <div class="mt-2">
           <a href="data:${mime};base64, ${result.data.face}" download="Tracking_Image_${result.data.nin}.${ext}" class="btn btn-sm btn-outline-primary w-100">
              <i class="bi bi-download"></i> Download Photo
           </a>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="table-responsive">
        <table class="table table-sm">
          <tbody>
            <tr>
              <th style="width: 40%;">Tracking Number</th>
              <td>${result.data.trackingid}</td>
            </tr>
            <tr>
              <th>NIN</th>
              <td><span id="nin_no">${result.data.nin}</span></td>
            </tr>
            <tr>
              <th>First Name</th>
              <td>${result.data.firstname}</td>
            </tr>
            <tr>
              <th>Surname</th>
              <td>${result.data.lastname}</td>
            </tr>
            <tr>
              <th>Middle Name</th>
              <td>${result.data.middlename}</td>
            </tr>
            <tr>
              <th>Gender</th>
              <td>${result.data.gender === 'm' ? 'Male' : result.data.gender === 'f' ? 'Female' : 'Not Specified'}</td>
            </tr>
             <tr>
              <th>ZONE</th>
              <td>${result.data.state}</td>
            </tr>
            <tr>
              <th>Town</th>
              <td>${result.data.town ?? ''}</td>
            </tr>
            <tr>
              <th>Address</th>
              <td>${result.data.address}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
`;


$("#download").removeClass("d-none"); // show
                $("#downloadDiv").show();

            } else {
                hideLoader();
                $("#errorMsg").show();
                $("#message").html("Invalid Response");

                setTimeout(function () {
                    $("#errorMsg").fadeOut();
                }, 30000);
            }
        },
        error: function (data) {
            hideLoader();
            $.each(data.responseJSON.errors, function (key, value) {
                $("#errorMsg").show();
                $("#message").html(value);
            });
            setTimeout(function () {
                $("#errorMsg").fadeOut();
            }, 30000);
        },
    });
});

$("#regularSlip").on("click", function (event) {
    let getNIN = $("#nin_no").html();

    fetch("/user/regularSlip/" + getNIN, {
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
                let filename = "document.pdf";
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

$("#standardSlip").on("click", function (event) {
    let getNIN = $("#nin_no").html();

    fetch("/user/standardSlip/" + getNIN, {
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

$("#premiumSlip").on("click", function (event) {
    let getNIN = $("#nin_no").html();

    fetch("/user/premiumSlip/" + getNIN, {
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


