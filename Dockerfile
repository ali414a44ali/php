# نسخة PHP مع Apache
FROM php:8.2-apache

# نسخ كامل ملفات المشروع إلى مجلد العمل
COPY . /var/www/html/
WORKDIR /var/www/html/

# تعيين أذونات التنفيذ للسكربت (.sh)
RUN chmod +x start.sh

# تشغيل السكربت (اختياري – حسب محتوى start.sh)
CMD ["./start.sh"]

# فتح المنفذ 80
EXPOSE 80
