# Base image Node 20
FROM node:20

# Set working directory
WORKDIR /app

# Copy package.json & package-lock.json (npm) / yarn.lock (jika pakai yarn)
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy semua file frontend
COPY . .

# Expose port 3000 for Next.js dev server
EXPOSE 3000

# Run Next.js dev server
CMD ["npm", "run", "dev"]
