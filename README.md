**Face Recognition Attendance System**

A smart attendance management system that uses facial recognition technology and deep learning to automatically identify students and record attendance.
This system reduces manual effort, prevents proxy attendance, and improves accuracy for classrooms, offices, and events.

---

## **📋 Features**

👨‍💼 Role-based login system (Admin & Lecturer)

📸 Capture and store multiple face images per student

🤖 Real-time face detection and recognition

🗂 Manage students, courses, units, and venues

📊 Attendance records stored in database

📤 Export attendance reports to Excel

🔐 Prevents fake or proxy attendance

🖥 User-friendly web interface

## Project Structure

````
## Project Structure

```plaintext
Face-Recognition-Attendance-System/
├── database/
│   ├── attendance-db.sql         # SQL file to set up the database
│   └── database_connection.php   # Database connection script
├── models/
│   └── face-api-models.js        # JavaScript models for Face API
├── resources/
│   ├── assets/
│   │   ├── css/                  # CSS files
│   │   └── javascript/           # JavaScript files
│   ├── images/                   # Images directory
│   ├── labels/                   # Stored images of registered students
│   ├── lib/
│   │   └── global-functions.php  # Global PHP functions
│   ├── pages/
│   │   ├── admin/                # Admin-specific pages
│   │   ├── lecturer/             # Lecturer-specific pages
│   │   └── login.php             # Login page
├── index.php                     # Main entry point for all pages
├── .htaccess                     # Redirect rules
└── README.md                     # Project documentation


````

## Technologies Used

Frontend: HTML, CSS, JavaScript

Backend: PHP

Database: MySQL

Face Recognition: Face-api.js (based on TensorFlow.js)

Server: XAMPP (Apache + MySQL)

Export: Excel (CSV/XLS)



Project Implementation Methodology
1️⃣ Face Data Collection

Admin registers students.

System captures 5 or more facial images per student.

Images are stored in the labels/ folder and linked to student records.

2️⃣ Face Detection

Webcam captures live video.

Face-api.js detects faces using deep learning models.

Facial landmarks and descriptors are extracted.

3️⃣ Face Recognition

Extracted facial features are compared with stored face descriptors.

Euclidean distance is used to match the closest face.

If matched, student identity is confirmed.

4️⃣ Attendance Recording

Recognized student ID is saved into the database with:

Date

Time

Course

Unit

Venue

5️⃣ Report Generation

Lecturer can export attendance records to Excel for documentation.



## **🚀 Setup Procedure**

Follow these steps to set up and run the project:

### **1. Clone or Download the Repository**

- Clone the repository using Git:
  ```bash
  git clone https://github.com/Suraia007/Face-Recognition-Attendance-System.git
  ```
  -Download zip file

### **2. Place the Project in the Server Directory**

If you’re using XAMPP, place the project folder inside the `htdocs` directory:

```plaintext
xampp/htdocs/Face-Recognition-Attendance-System
```

Use a simple folder name, as it will be part of the URL (e.g., attendance-system).

### **3. Start XAMPP**

- Open the XAMPP Control Panel.
- Start the **Apache** and **MySQL** services.

### **4. Set Up the Database**


-Open phpMyAdmin

-Create database: attendance_db

-Import attendance-db.sql

 


### **5. Launch the Application**

Visit the application in your browser:

```plaintext
http://localhost/Face-Recognition-Attendance-System
```

## 🧑‍💻 User Guide

### 1. Login as Administrator

- **Email**: `admin@gmail.com`
- **Password**: `@admin_`
  
-Add students

-Add lecturers

-Manage courses and venues

-Capture student face images

⚠ Capture at least 5 clear images per student for better accuracy.

⚠️ **Important**:

- Ensure to add at least **two students** and capture **five clear images** for each.
- Poor image quality will affect recognition accuracy. You can retake any image by clicking on it.

### 2. Login as Lecturer



- **Email**: `vashkarkar@gmail.com`
- **Password**: `@vaskar_`

As a lecturer:

- Select a course, unit, and venue on the home page.
- Launch the **Face Recognition** feature to begin attendance.

 Advantages:

Saves time compared to manual attendance

Reduces fraud and proxy attendance

High accuracy with multiple images

Easy to use

Paperless system


Security & Privacy:

Role-based authentication

Secure database storage

Only authorized users can access attendance data



