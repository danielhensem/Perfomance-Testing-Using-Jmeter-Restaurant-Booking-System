# Install JMeter - Quick Guide

## Step 1: Download JMeter

1. Go to: https://jmeter.apache.org/download_jmeter.cgi
2. Download: **apache-jmeter-5.6.zip** (or latest version)
3. Extract to: `C:\apache-jmeter-5.6` (or any location you prefer)

## Step 2: Add to PATH (Optional but Recommended)

1. Right-click **This PC** → **Properties** → **Advanced system settings**
2. Click **Environment Variables**
3. Under **System variables**, find **Path** → Click **Edit**
4. Click **New** → Add: `C:\apache-jmeter-5.6\bin`
5. Click **OK** on all dialogs

## Step 3: Verify Installation

Open PowerShell and run:
```powershell
jmeter -v
```

You should see the JMeter version information.

## Alternative: Install via Chocolatey (If you have it)

```powershell
choco install jmeter
```

## Quick Test

After installation, try:
```powershell
jmeter -v
```

If it shows version info, JMeter is ready to use!
