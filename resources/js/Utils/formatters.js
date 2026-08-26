/**
 * Utility Helper untuk Formatting Data di Vue / Inertia App
 */

/**
 * Memformat string tanggal (YYYY-MM-DD) menjadi format Indonesia (DD MMMM YYYY)
 * Contoh: "1945-08-17" -> "17 Agustus 1945"
 * 
 * @param {string|Date} dateString 
 * @returns {string}
 */
export const formatDateIndonesia = (dateString) => {
  if (!dateString) return '-';
  
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return dateString;

  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  }).format(date);
};

/**
 * Memformat tanggal dan waktu (DD MMMM YYYY, HH:mm)
 * Contoh: "2026-08-20 07:15:00" -> "20 Agustus 2026, 07:15"
 * 
 * @param {string|Date} dateString 
 * @returns {string}
 */
export const formatDateTimeIndonesia = (dateString) => {
  if (!dateString) return '-';
  
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return dateString;

  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

/**
 * Memformat nomor telepon/WA ke format internasional (+62)
 * Contoh: "08123456789" -> "628123456789"
 * 
 * @param {string} phone 
 * @returns {string}
 */
export const formatWhatsAppNumber = (phone) => {
  if (!phone) return '';
  let cleaned = phone.replace(/\D/g, '');
  if (cleaned.startsWith('0')) {
    cleaned = '62' + cleaned.slice(1);
  }
  return cleaned;
};