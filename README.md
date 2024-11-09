# A.S.L.I (Advanced Surveillance Logic-Based Identification of Deep Fakes) 📹🔍

## Overview
A.S.L.I is a real-time deep fake detection system that combines AI and blockchain technology to ensure media authenticity. The system utilizes **LSTM** and **ResNeXt-50** deep learning models for detection, processes frames with **OpenCV**, and leverages **blockchain** for result verification. The frontend is built with **Laravel** for a chatbot UI, and the backend runs on **Flask**, integrated with **Vultr's serverless inference** for scalable, reliable processing.

## Features 🌟
- **Real-Time Deep Fake Detection:** Accurate and efficient identification of manipulated media.
- **Scalable Infrastructure:** Utilizes Vultr's cloud services for compute, storage, and serverless inference.
- **Conversational Chatbot UI:** Interactive, history-based chatbot interface for ongoing user engagement.

## Architecture 🛠️
The system architecture consists of:
- **Frontend:** Laravel-based chatbot UI
- **Backend:** Flask server processing requests and connecting with Vultr’s serverless inference
- **AI Models:** LSTM & ResNeXt-50 for sequence and spatial analysis

## API Endpoints 🗂️
- **/detect** (POST): Submit video for deep fake detection

## Installation 🛠️

### Prerequisites
- **Python** (v3.8 or later)
- **Node.js** and **npm** (for frontend setup)
- **Flask** and **OpenCV** (for backend processing)
- **Vultr** account (for serverless inference setup)

### Steps
1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/ASLI-DeepFake-Detection.git
   cd ASLI-DeepFake-Detection

2. **Create a folder named model**
3. **Frontend (Laravel) Setup:**
    Navigate to the frontend directory and install dependencies.
    ```bash
    composer install
    php artisan serve
4. **Backend (Flask) Setup:**
    Create and activate a virtual environment, then install requirements.
    ```bash
    python3 -m venv env
    source env/bin/activate
    pip install -r requirements.txt
    Setup Vultr Serverless Inference and enter VULR_API_KEY in .env

**Follow Vultr's serverless documentation for deployment and integration with Flask.**
Run the Project:
    Start both the frontend and backend services, ensuring Vultr inference is active.
    
**Usage 💬**
Access the chatbot UI to interact with the system.
Submit videos for detection using the /detect endpoint.
Use /verify for blockchain verification and /status to monitor progress.

**Technologies Used 🧩**
Frontend: Laravel, JavaScript
Backend: Flask, OpenCV, TensorFlow, LSTM & ResNeXt-50 models
Cloud Services: Vultr (Compute Instances, Block Storage, Serverless Inference)
Blockchain: Verification layer for secure result validation
