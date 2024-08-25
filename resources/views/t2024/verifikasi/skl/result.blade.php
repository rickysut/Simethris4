<!DOCTYPE html>
<html lang="en" class="root-text-sm">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ringkasan Pemeriksaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

	<style>
		.root-text-sm{
			font-size: 15px;
		}
		.container.page-cover {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
			width: 100%;
		}

		.pagebreak {
			page-break-before: always
		}

		.list-group-numbered {
			counter-reset: list-number;
			padding-left: 0;
		}

		.list-group-numbered > .list-group-item {
			position: relative;
			padding-left: 2.5rem; /* Adjust padding for number spacing */
		}

		.list-group-numbered > .list-group-item:before {
			counter-increment: list-number;
			content: counter(list-number) ".";
			position: absolute;
			left: 1rem;
			top: 50%;
			transform: translateY(-50%);
			font-weight: bold;
			color: #007bff; /* Bootstrap primary color */
		}
	</style>
</head>

<body>
	<div class="main small">
    	@include('t2024.verifikasi.skl.reportContentTitle')
    	@include('t2024.verifikasi.skl.reportContent')
	</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

</body>

</html>
