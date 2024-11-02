'use client';

import { useState } from 'react';
import {
  ErrorOutline as AlertCircleIcon,
  CheckCircle as CheckCircleIcon,
  Loop as LoaderIcon
} from '@mui/icons-material';
import Navbar from '@/components/navbar/Navbar';

// Simple Alert component to replace shadcn/ui Alert
const Alert = ({ children, variant = 'default', className = '' }) => {
  const bgColor = variant === 'destructive' ? 'bg-red-50' : 'bg-blue-50';
  const textColor = variant === 'destructive' ? 'text-red-700' : 'text-blue-700';
  const borderColor = variant === 'destructive' ? 'border-red-200' : 'border-blue-200';

  return (
    <div className={`p-4 rounded-md border ${bgColor} ${textColor} ${borderColor} ${className}`}>
      <div className="flex items-center">
        <AlertCircleIcon className="h-5 w-5 mr-2" />
        {children}
      </div>
    </div>
  );
};

const ResultDisplay = ({ result, confidence }) => {
  const isFake = result?.toLowerCase().includes('fake');
  
  return (
    <div className="mt-8 p-6 bg-white shadow-lg rounded-lg text-center">
      <div className="flex items-center justify-center mb-4">
        {isFake ? (
          <AlertCircleIcon className="h-12 w-12 text-red-500" />
        ) : (
          <CheckCircleIcon className="h-12 w-12 text-green-500" />
        )}
      </div>
      <h2 className={`text-2xl font-bold mb-2 ${isFake ? 'text-red-600' : 'text-green-600'}`}>
        {result}
      </h2>
      <div className="mt-4 bg-gray-100 rounded-full h-4 overflow-hidden">
        <div
          className={`h-full ${isFake ? 'bg-red-500' : 'bg-green-500'}`}
          style={{ width: `${confidence}%` }}
        />
      </div>
      <p className="text-gray-600 mt-2">
        Confidence: {confidence.toFixed(2)}%
      </p>
    </div>
  );
};

const UploadForm = ({ onSubmit }) => {
  const handleSubmit = (event) => {
    event.preventDefault();
    const file = event.target.video.files[0];
    if (file) {
      onSubmit(file);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="p-6 bg-white rounded-lg shadow-lg">
      <div className="space-y-4">
        <label className="block">
          <span className="text-gray-700">Upload Video</span>
          <input
            type="file"
            name="video"
            accept="video/*"
            className="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
              focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
          />
        </label>
        <button
          type="submit"
          className="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Analyze Video
        </button>
      </div>
    </form>
  );
};

export default function Home() {
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleUpload = async (videoFile) => {
    setLoading(true);
    setError(null);
    const formData = new FormData();
    formData.append('video', videoFile);

    try {
      const response = await fetch('http://127.0.0.1:3000/Detect', {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) {
        throw new Error('Failed to process video');
      }

      const data = await response.json();
      setResult(data);
    } catch (error) {
      setError('Error processing video. Please try again.');
      console.error('Error uploading video:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <Navbar />
      
      <div className="container mx-auto px-4 py-12">
        <h1 className="text-center text-4xl font-bold text-gray-900 mb-4">
          Detect Fake Videos with AI
        </h1>
        <p className="text-center text-gray-600 mb-8 max-w-2xl mx-auto">
          Upload your video to analyze it for potential manipulation using our advanced AI detection system.
        </p>
        
        <div className="max-w-xl mx-auto">
          {error && (
            <Alert variant="destructive" className="mb-6">
              {error}
            </Alert>
          )}
          
          {loading ? (
            <div className="text-center p-8 bg-white rounded-lg shadow-lg">
              <LoaderIcon className="h-12 w-12 animate-spin mx-auto text-blue-600 mb-4" />
              <p className="text-gray-600">Processing your video...</p>
            </div>
          ) : (
            <UploadForm onSubmit={handleUpload} />
          )}
          
          {result && !loading && (
            <ResultDisplay result={result.output} confidence={result.confidence} />
          )}
        </div>
      </div>
    </div>
  );
}