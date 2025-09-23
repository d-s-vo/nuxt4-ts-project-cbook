# Windsurf Deployment Guide

This guide will walk you through deploying your Nuxt.js application to Windsurf.

## Prerequisites

- Node.js 18+
- pnpm
- Git
- Windsurf account and access credentials

## Step 1: Prepare Your Application

1. Ensure your application builds correctly:
   ```bash
   pnpm install
   pnpm build
   ```

2. Create a `.windsurf` directory in your project root:
   ```bash
   mkdir .windsurf
   ```

3. Create a `deploy.yml` file in the `.windsurf` directory with the following content:
   ```yaml
   name: vue-ts-cbook
   build:
     command: pnpm install && pnpm build
     output: .output
   ```

## Step 2: Deploy Using Windsurf CLI

1. Install the Windsurf CLI (if not already installed):
   ```bash
   # Follow the installation instructions from your Windsurf dashboard
   # This typically involves downloading and installing their CLI tool
   ```

2. Authenticate with Windsurf:
   ```bash
   windsurf login
   ```

3. Deploy your application:
   ```bash
   windsurf deploy
   ```

## Step 3: Configure Your Deployment

1. After the initial deployment, you'll receive a URL where your application is hosted
2. Configure custom domains (if needed) through the Windsurf dashboard
3. Set up environment variables in the Windsurf dashboard if your application requires them

## Step 4: Set Up Continuous Deployment (Optional)

1. Connect your Git repository to Windsurf through the dashboard
2. Configure build settings:
   - Build command: `pnpm install && pnpm build`
   - Output directory: `.output`
3. Enable automatic deployments on push to your main branch

## Troubleshooting

### Build Failures
- Check the build logs in the Windsurf dashboard
- Ensure all dependencies are listed in `package.json`
- Verify Node.js version compatibility

### Application Not Starting
- Check the runtime logs in the Windsurf dashboard
- Ensure the `output` directory in `deploy.yml` matches your build output
- Verify all environment variables are properly set

## Support

For additional help, refer to the official Windsurf documentation or contact their support team.

---
*Note: If you encounter any issues with the Windsurf CLI, please verify you're using the latest version and that you have the correct permissions to deploy to your organization's Windsurf account.*
