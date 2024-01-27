# Start from the latest node image
FROM node:18.12.0

LABEL maintainer="Hasmukh Mistry <hasmukhmistry137@gmail.com>"

# Copy startup script
COPY start.vue.sh /start.vue.sh
RUN chmod +x /start.vue.sh

# Expose port 3001
EXPOSE 3001

# Run startup script
CMD ["/start.vue.sh"]
