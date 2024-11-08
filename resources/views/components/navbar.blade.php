{{-- resources/views/components/navbar.blade.php --}}
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-flex">
            <div class="navbar-brand">
                <span class="navbar-title">Video Detector</span>
            </div>
            <div class="navbar-links">
                <a href="#" class="navbar-link">Home</a>
                <a href="#" class="navbar-link">About</a>
                <a href="#" class="navbar-link">Contact</a>
            </div>
        </div>
    </div>
</nav>

<style>
    .navbar {
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navbar-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .navbar-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 64px;
    }

    .navbar-brand {
        font-size: 1.25rem;
        font-weight: bold;
        color: #1a202c;
    }

    .navbar-links {
        display: flex;
        align-items: center;
    }

    .navbar-link {
        color: #1a202c;
        margin-left: 16px;
        font-size: 1rem;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .navbar-link:hover {
        color: #4a5568;
    }
</style>
