<style>
    .hidden {
        display: none;
    }

    #confirm-dialog {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 20px;
        text-align: center;
    }

    #confirm-dialog p {
        margin: 0 0 20px;
    }

    #confirm-button,
    #cancel-button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        margin: 0 10px;
    }
</style>
</head>

<body>
    <div id="confirm-dialog" class="hidden">
        <p>Are you sure you want to do this?</p>
        <button id="confirm-button">Yes</button>
        <button id="cancel-button">No</button>
    </div>

    <button id="main-button">Click me</button>


</body>
<script>
    document.getElementById("main-button").addEventListener("click", function() {
        document.getElementById("confirm-dialog").classList.remove("hidden");
    });

    document.getElementById("confirm-button").addEventListener("click", function() {
        // Handle confirmation action
        document.getElementById("confirm-dialog").classList.add("hidden");
    });

    document.getElementById("cancel-button").addEventListener("click", function() {
        // Handle cancel action
        document.getElementById("confirm-dialog").classList.add("hidden");
    });
</script>

</html>