from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch()
    page = browser.new_page()
    page.goto("http://localhost:8080/login")
    page.fill("input[name=username]", "testuser")
    page.fill("input[name=password]", "wrongpassword")
    page.click("button[type=submit]")
    page.screenshot(path="/app/jules-scratch/verification/login_error.png")
    browser.close()

with sync_playwright() as playwright:
    run(playwright)
