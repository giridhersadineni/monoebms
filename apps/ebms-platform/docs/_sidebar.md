<!-- _sidebar.md -->

- **Getting Started**
  - [Overview](README.md)
  - [Local Setup](setup.md)
  - [Docker](setup.md#docker)

- **Architecture**
  - [System Overview](architecture.md)
  - [Module Structure](architecture.md#module-structure)
  - [Authentication](architecture.md#authentication)
  - [Security Middleware](architecture.md#security-middleware)

- **Database**
  - [Schema Overview](database.md)
  - [Migrations](database.md#migrations)
  - [Key Relationships](database.md#key-relationships)

- **Models**
  - [Student](models/student.md)
  - [Exam](models/exam.md)
  - [ExamEnrollment](models/exam-enrollment.md)
  - [ExamFeeRule](models/exam-fee-rule.md)
  - [Subject](models/subject.md)
  - [Result & GPA](models/result-gpa.md)
  - [Course & CourseGroup](models/course.md)

- **Services**
  - [FeeCalculatorService](services/fee-calculator.md)
  - [ChallanPdfService](services/challan-pdf.md)
  - [GpaCalculatorService](services/gpa-calculator.md)

- **Student Module**
  - [Authentication](modules/student/auth.md)
  - [Dashboard](modules/student/dashboard.md)
  - [Enrollment Flow](modules/student/enrollment.md)
  - [Challan (Fee Receipt)](modules/student/challan.md)
  - [Results & GPA](modules/student/results.md)
  - [Profile & Photos](modules/student/profile.md)
  - [Revaluation](modules/student/revaluation.md)

- **Admin Module**
  - [Authentication](modules/admin/auth.md)
  - [Exam Management](modules/admin/exams.md)
  - [Fee Rules](modules/admin/fee-rules.md)
  - [Student Management](modules/admin/students.md)
  - [Enrollment Management](modules/admin/enrollments.md)
  - [Courses & Groups](modules/admin/courses.md)
  - [Papers (Subjects)](modules/admin/subjects.md)

- **Legacy Migration**
  - [Command Reference](legacy/migrate-command.md)
  - [Data Mapping](legacy/data-mapping.md)

- **Deployment**
  - [Deploy to Production](deployment.md)
  - [Post-deploy Checklist](deployment.md#checklist)
