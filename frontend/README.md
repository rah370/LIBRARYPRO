# Library Management System - Frontend

A modern, responsive frontend for the Library Management System built with HTML5, CSS3, and vanilla JavaScript.

## 🌟 Features

### 🎨 Modern Design
- **Responsive Layout**: Works perfectly on desktop, tablet, and mobile devices
- **Modern UI/UX**: Clean, intuitive interface with smooth animations
- **Bootstrap 5**: Latest Bootstrap framework for consistent styling
- **Font Awesome Icons**: Beautiful icons throughout the interface
- **Custom CSS**: Tailored styling with CSS variables and modern techniques

### 📱 Pages Included

1. **Landing Page** (`index.html`)
   - Hero section with call-to-action
   - Features showcase
   - Statistics counters
   - Contact form
   - Login modal with role selection

2. **Student Dashboard** (`student-dashboard.html`)
   - Browse books interface
   - Advanced search functionality
   - My borrowed books section
   - Book details modal
   - Grid/List view toggle

3. **Welcome Page** (`welcome.html`)
   - Entry point with navigation options
   - Links to frontend demo and backend system

### ⚡ JavaScript Features

- **Single Page Application (SPA)**: Smooth navigation between sections
- **Real-time Search**: Instant book search with debouncing
- **Interactive Components**: Modals, forms, and dynamic content
- **Accessibility**: Keyboard navigation and ARIA labels
- **Performance Optimized**: Lazy loading and efficient DOM manipulation

## 🗂️ File Structure

```
frontend/
├── index.html              # Main landing page
├── student-dashboard.html   # Student dashboard demo
├── welcome.html            # Welcome/entry page
├── css/
│   ├── style.css          # Main stylesheet
│   └── dashboard.css      # Dashboard-specific styles
├── js/
│   ├── main.js           # Landing page functionality
│   └── student-dashboard.js # Dashboard functionality
└── assets/               # Images and other assets
```

## 🚀 Getting Started

### Option 1: Standalone Frontend Demo
```bash
# Navigate to frontend directory
cd frontend

# Open with any local server
python3 -m http.server 8000
# or
npx serve .
# or simply open index.html in your browser
```

### Option 2: Integrated with Backend
The frontend is designed to work with the CodeIgniter backend:
- Place frontend files in the main project directory
- Access via `http://localhost:8080/frontend/`

## 🎯 Navigation Guide

### Landing Page Features
- **Hero Section**: Introduction and main call-to-action
- **Features**: Showcase of system capabilities
- **Statistics**: Animated counters
- **About**: System information
- **Contact**: Contact form (demo functionality)

### Student Dashboard Demo
- **Browse Books**: Grid/List view of available books
- **My Books**: View borrowed books and due dates
- **Advanced Search**: Filter books by multiple criteria
- **Book Details**: Modal with complete book information

## 🎨 Design Features

### Color Scheme
- Primary: `#2c3e50` (Dark blue-gray)
- Secondary: `#3498db` (Blue)
- Success: `#27ae60` (Green)
- Warning: `#f39c12` (Orange)
- Danger: `#e74c3c` (Red)

### Typography
- Font Family: Inter, Segoe UI, system fonts
- Responsive font sizes
- Clear hierarchy with proper contrast

### Animations
- Smooth page transitions
- Hover effects on interactive elements
- Loading animations
- Counter animations for statistics
- Parallax effects on scroll

## 🔧 Customization

### CSS Variables
The design uses CSS custom properties for easy theming:

```css
:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    /* ... more variables */
}
```

### JavaScript Configuration
Key settings can be modified in the JavaScript files:

```javascript
// In student-dashboard.js
this.booksPerPage = 12; // Change items per page
this.currentView = 'list'; // Default view: 'grid' or 'list'

// In main.js
const observerOptions = {
    threshold: 0.1, // Animation trigger point
    rootMargin: '0px 0px -50px 0px'
};
```

## 📱 Responsive Breakpoints

- Mobile: `< 576px`
- Small: `576px - 768px`
- Medium: `768px - 992px`
- Large: `992px - 1200px`
- Extra Large: `> 1200px`

## ♿ Accessibility Features

- **Keyboard Navigation**: Full keyboard support
- **Screen Reader Support**: Proper ARIA labels and structure
- **Focus Management**: Visible focus indicators
- **Color Contrast**: WCAG compliant color ratios
- **Skip Links**: Navigation shortcuts for assistive technology

## 🔄 Integration with Backend

The frontend is designed to integrate seamlessly with the CodeIgniter backend:

### Authentication Flow
```javascript
// Redirect to backend login
function redirectToLogin(role) {
    if (role === 'admin') {
        window.location.href = '../index.php/auth/login?role=admin';
    } else {
        window.location.href = '../index.php/auth/login?role=student';
    }
}
```

### API Integration Points
- Book search and filtering
- User authentication
- Borrow/return operations
- Notifications and alerts

## 🎭 Demo Data

The frontend includes sample data for demonstration:
- Sample books with realistic information
- Mock user interactions
- Simulated API responses
- Loading states and error handling

## 🛠️ Browser Support

- **Modern Browsers**: Chrome 80+, Firefox 75+, Safari 13+, Edge 80+
- **Mobile**: iOS Safari 13+, Chrome Mobile 80+
- **Features Used**: CSS Grid, Flexbox, ES6+, Fetch API, IntersectionObserver

## 📄 License

This frontend is part of the Library Management System project and follows the same licensing terms.

## 🤝 Contributing

1. Follow the existing code style and structure
2. Test on multiple devices and browsers
3. Ensure accessibility compliance
4. Update documentation for new features

## 🐛 Known Issues

- Some animations may not work on older browsers
- Demo functionality doesn't persist data
- Mobile keyboard may affect viewport height calculations

## 🔮 Future Enhancements

- Progressive Web App (PWA) capabilities
- Dark mode toggle
- Advanced filtering options
- Real-time notifications
- Offline functionality
- Multi-language support

---

**Note**: This frontend provides a complete user interface for the Library Management System. While it includes demo functionality, it's designed to integrate with the CodeIgniter backend for full operational capability.