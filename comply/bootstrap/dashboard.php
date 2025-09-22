<style>
    /* Custom color scheme matching the application's teal theme */
    :root {
        --primary: #0FA4AF;
        --primary-dark: #024950;
        --primary-light: #6CCED7;
        --blue-accent: #5379eb; /* Blue accent color from the screenshot */
    }
    
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
        background-color: var(--primary-dark) !important;
        border-color: var(--primary-dark) !important;
    }
    
    .text-primary, .font-weight-bold.text-primary {
        color: var(--primary-dark) !important;
    }
    
    .border-left-primary {
        border-left-color: var(--primary) !important;
    }
    
    .bg-primary {
        background-color: var(--primary) !important;
    }
    
    /* Chart colors */
    .bg-info, .bg-success, .bg-warning, .bg-danger {
        background-color: var(--primary) !important;
        opacity: 0.8;
    }
    
    /* Adjust navbar color */
    .topbar {
        background-color: white !important;
    }
    
    /* Card headers */
    .card-header {
        background-color: rgba(2, 73, 80, 0.05) !important;
    }
    
    /* Chart colors override - but exclude search icon */
    .text-info i.fas, .text-success i.fas, .text-warning i.fas, .text-primary i.fas {
        color: var(--primary-dark) !important;
    }
    
    /* Style search button like in the screenshot */
    .navbar-search .input-group-append .btn {
        display: inline-block !important; /* Show the button */
        background-color: var(--blue-accent) !important; /* Blue accent color from screenshot */
        border-color: var(--blue-accent) !important;
        border-radius: 0 0.35rem 0.35rem 0 !important; /* Round right corners */
    }
    
    /* Fix for the search icon */
    .navbar-search .input-group-append .btn i.fas.fa-search {
        color: white !important;
    }
    
    /* Style the search input field */
    .navbar-search .form-control {
        border-radius: 0.35rem 0 0 0.35rem !important; /* Round left corners only */
    }
</style> 