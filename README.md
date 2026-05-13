🌐 Mustafa Yusuf Özkan — Personal Portfolio

"INITIALIZING_DOMAIN..."

A full-stack personal portfolio website with an anime-inspired dark aesthetic, built to showcase projects, skills, and provide a live contact channel. 

📋 Project Overview
This portfolio is a single-page web application that presents:

Hero section — Introduction with a glitch-text name animation and character artwork
About — Personal background, university info, MBTI, and hobbies
Skills — Animated progress-bar "combat stats" for core technologies
Projects — Dynamically loaded project cards fetched from a MySQL database via a PHP REST endpoint
Contact — A form that saves visitor messages directly to the database

The project was built as a course submission and doubles as a real personal portfolio hosted locally (XAMPP) with a PHP/MySQL backend.

🛠️ Technologies Used
LayerTechnologyFrontendHTML5, CSS3, JavaScript (Vanilla)BackendPHP 8DatabaseMySQL (via PDO)Local ServerXAMPP (Apache + MySQL)Version ControlGit & GitHub

📁 File Structure
portfolio/
│
├── index.html          # Main single-page layout (all sections)
├── style.css           # Dark-theme styling, animations, responsive layout
├── script.js           # Client-side logic: contact form, project fetching
│
├── db.php              # PDO database connection (credentials config)
├── get_projects.php    # API endpoint — returns projects as JSON
├── save_message.php    # API endpoint — saves contact form submissions
│
├── admin.php           # Admin dashboard — view messages & manage projects
├── login.php           # Admin login page (session-based auth)
├── setup_admin.php     # One-time admin account setup script
│
└── urahara.webp        # Hero section character image

⚙️ How It Was Built
1. Frontend (HTML / CSS / JS)
The entire UI lives in a single index.html file structured into semantic sections (<header>, <section>). Navigation is handled by smooth-scroll anchor links via the fixed top navbar.
style.css applies a dark color palette (#1a1a1a backgrounds, #FFE368 yellow accent, white text) and includes:

A CSS glitch-text keyframe animation on the hero name
Animated skill bars that fill on load
A responsive grid layout for the projects section

script.js handles two main responsibilities:

Fetching projects — On page load it calls get_projects.php, receives JSON, and dynamically builds project cards and injects them into #projects-grid
Contact form — Intercepts submit, validates fields client-side, POSTs data to save_message.php, and displays success/error feedback without reloading the page

2. Backend (PHP + MySQL)
The database connection is centralized in db.php using PHP's PDO extension with PDO::ERRMODE_EXCEPTION for safe error handling. All other PHP files include db.php to reuse the connection.

get_projects.php — Queries the projects table and returns rows as a JSON array; consumed by the frontend fetch call
save_message.php — Accepts a POST request with name, email, and message fields, sanitizes them, and inserts into the messages table using a prepared statement to prevent SQL injection
admin.php — A protected dashboard (requires login session) that lists received contact messages and allows CRUD operations on projects
login.php / setup_admin.php — Session-based authentication; setup_admin.php is a one-time script to hash and store the admin password

3. Database Schema
The application uses a MySQL database named portfolio_db with two tables:
sql-- Projects displayed on the portfolio
CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  description TEXT,
  tech_stack VARCHAR(255),
  link VARCHAR(255)
);

-- Messages submitted via the contact form
CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  message TEXT,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

🚀 Setup & Running Locally

Install XAMPP and start Apache + MySQL services
Clone the repository into the htdocs folder:

bash   git clone https://github.com/muffylamingo/portfolio.git

Create the database — Open phpMyAdmin, create a database called portfolio_db, and run the SQL above to create the tables
Configure credentials — Open db.php and update $username / $password if your MySQL setup differs from the XAMPP default
Set up admin account — Visit http://localhost/portfolio/setup_admin.php once to create the admin user, then delete or disable that file
Open the portfolio — Navigate to http://localhost/portfolio/index.html


✨ Key Features

Dynamic project loading — Projects are stored in a database and rendered client-side, making it easy to add new work without touching HTML
Admin panel — A secure backend dashboard to manage project entries and read contact messages
Prepared statements — All database writes use PDO prepared statements, protecting against SQL injection
Anime-inspired UI — Terminal-style section headers, glitch animations, and a Jujutsu Kaisen color palette give the portfolio a distinctive personal identity
No frameworks — Built entirely in vanilla HTML, CSS, and JavaScript to demonstrate core web fundamentals


👤 Author
Mustafa Yusuf Özkan
3rd-year Software Engineering Student — Haliç University, Istanbul
Specializing in .NET / C#
