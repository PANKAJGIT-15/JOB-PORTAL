<?php

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/helpers/session.php';

$pageTitle = 'Home - Find Your Next Career Opportunity';
require_once dirname(__DIR__) . '/views/layouts/header.php';
?>

<div class="hero-section">
    <h1>Find Your Dream Job Today</h1>
    <p>Discover thousands of career opportunities across top engineering, marketing, and product firms.</p>
    
    <div class="search-bar-card">
        <form action="/job-portal/public/" method="GET" class="hero-search-form">
            <input type="text" name="q" placeholder="Job title, keywords, or company">
            <button type="submit" class="btn-primary">Search Jobs</button>
        </form>
    </div>
</div>

<div class="features-grid">
    <div class="feature-box">
        <h3>For Job Seekers</h3>
        <p>Build your profile, explore high-paying roles, and apply directly to top employers.</p>
    </div>
    <div class="feature-box">
        <h3>For Employers</h3>
        <p>Post open requirements, review candidate applications, and hire talent efficiently.</p>
    </div>
    <div class="feature-box">
        <h3>Verified Roles</h3>
        <p>Every role is vetted for authenticity and real-time active recruitment.</p>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/views/layouts/footer.php'; ?>