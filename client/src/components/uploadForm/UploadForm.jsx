// components/uploadForm/UploadForm.js
'use client';
import { useState } from 'react';
import { CloudUpload as UploadIcon } from '@mui/icons-material';

export default function UploadForm({ onSubmit }) {
  const [videoFile, setVideoFile] = useState(null);
  const [dragActive, setDragActive] = useState(false);

  const handleDrag = (e) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === "dragenter" || e.type === "dragover") {
      setDragActive(true);
    } else if (e.type === "dragleave") {
      setDragActive(false);
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setDragActive(false);
    
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('video/')) {
      setVideoFile(file);
    }
  };

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file && file.type.startsWith('video/')) {
      setVideoFile(file);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (videoFile) {
      onSubmit(videoFile);
    }
  };

  return (
    <form 
      className={`bg-white shadow-lg rounded-lg p-8 ${dragActive ? 'border-2 border-blue-500' : ''}`}
      onDragEnter={handleDrag}
      onDragLeave={handleDrag}
      onDragOver={handleDrag}
      onDrop={handleDrop}
      onSubmit={handleSubmit}
    >
      <div className="mb-6">
        <div className="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
          <UploadIcon className="mx-auto h-12 w-12 text-gray-400 mb-4" />
          <label className="block text-gray-700 text-lg font-medium mb-2">
            {videoFile ? videoFile.name : 'Drop your video here or click to upload'}
          </label>
          <p className="text-sm text-gray-500 mb-4">
            Supports MP4, MOV, AVI formats
          </p>
          <input
            type="file"
            onChange={handleFileChange}
            accept="video/*"
            className="hidden"
            id="video-upload"
          />
          <label
            htmlFor="video-upload"
            className="inline-block bg-blue-50 text-blue-600 px-4 py-2 rounded-md cursor-pointer hover:bg-blue-100 transition-colors"
          >
            Select Video
          </label>
        </div>
      </div>
      <button
        type="submit"
        disabled={!videoFile}
        className={`w-full py-3 px-4 rounded-md font-medium text-white transition-colors
          ${videoFile 
            ? 'bg-blue-600 hover:bg-blue-700' 
            : 'bg-gray-400 cursor-not-allowed'}`}
      >
        Detect Fake Video
      </button>
    </form>
  );
}