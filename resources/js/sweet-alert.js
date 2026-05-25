class CustomSweetAlert {
    constructor() {
        this.isOpen = false
        this.currentAlert = null
        this.toastContainer = null
        this.init()
    }

    init() {
        this.createModalStructure()
        this.createToastContainer()
        this.bindEvents()
    }

    createModalStructure() {
        // Create overlay
        this.overlay = document.createElement("div")
        this.overlay.className = "swal-overlay"
        this.overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        `

        // Create modal
        this.modal = document.createElement("div")
        this.modal.className = "swal-modal"
        this.modal.style.cssText = `
            background: white;
            border-radius: 12px;
            padding: 0;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            transform: scale(0.8);
            transition: transform 0.3s ease;
            overflow: hidden;
            position: relative;
        `

        this.overlay.appendChild(this.modal)
        document.body.appendChild(this.overlay)
    }

    createToastContainer() {
        // Create toast containers for different positions
        this.toastContainers = {}
        const positions = ["top-end", "top-start", "bottom-end", "bottom-start", "top-center", "bottom-center"]

        positions.forEach((position) => {
            const container = document.createElement("div")
            container.className = `toast-container-${position}`
            container.style.cssText = `
                position: fixed;
                z-index: 10000;
                pointer-events: none;
                ${position.includes("top") ? "top: 20px;" : "bottom: 20px;"}
                ${position.includes("end")
                    ? "right: 20px;"
                    : position.includes("start")
                        ? "left: 20px;"
                        : "left: 50%; transform: translateX(-50%);"
                }
            `
            document.body.appendChild(container)
            this.toastContainers[position] = container
        })
    }

    bindEvents() {
        this.overlayClickHandler = (e) => {
            if (e.target === this.overlay && this.allowOutsideClick) {
                this.close()
            }
        }

        this.keydownHandler = (e) => {
            if (e.key === "Escape" && this.isOpen && this.allowEscapeKey) {
                this.close()
            }
        }

        this.overlay.addEventListener("click", this.overlayClickHandler)
        document.addEventListener("keydown", this.keydownHandler)
    }

    show(options = {}) {
        const {
            title = "",
            text = "",
            type = "info",
            showConfirmButton = true,
            showCancelButton = false,
            confirmButtonText = "OK",
            cancelButtonText = "Cancel",
            confirmButtonColor = "#3085d6",
            cancelButtonColor = "#d33",
            timer = null,
            allowOutsideClick = true,
            allowEscapeKey = true,
            onConfirm = null,
            onCancel = null,
            onClose = null,
        } = options

        return new Promise((resolve) => {
            this.currentAlert = {
                resolve,
                onConfirm,
                onCancel,
                onClose,
                timer: null,
            }

            // Build modal content
            this.modal.innerHTML = this.buildModalContent({
                title,
                text,
                type,
                showConfirmButton,
                showCancelButton,
                confirmButtonText,
                cancelButtonText,
                confirmButtonColor,
                cancelButtonColor,
            })

            // Disable body scrolling
            document.body.style.overflow = "hidden"

            // Show modal
            this.overlay.style.display = "flex"
            setTimeout(() => {
                this.overlay.style.opacity = "1"
                this.modal.style.transform = "scale(1)"
            }, 10)

            this.isOpen = true

            // Bind button events
            this.bindButtonEvents()

            // Auto close timer
            if (timer) {
                this.currentAlert.timer = setTimeout(() => {
                    this.close({ isDismissed: true, dismissedByTimer: true })
                }, timer)
            }

            // Configure outside click and escape key
            this.allowOutsideClick = allowOutsideClick
            this.allowEscapeKey = allowEscapeKey
        })
    }

    buildModalContent({
        title,
        text,
        type,
        showConfirmButton,
        showCancelButton,
        confirmButtonText,
        cancelButtonText,
        confirmButtonColor,
        cancelButtonColor,
    }) {
        const iconHtml = this.getIconHtml(type)

        return `
            <div class="swal-header" style="padding: 30px 30px 20px; text-align: center;">
                <div class="swal-icon" style="margin-bottom: 20px;">
                    ${iconHtml}
                </div>
                ${title ? `<h2 class="swal-title" style="margin: 0 0 10px; font-size: 24px; font-weight: 600; color: #333; line-height: 1.3;">${title}</h2>` : ""}
                ${text ? `<p class="swal-text" style="margin: 0; font-size: 16px; color: #666; line-height: 1.5;">${text}</p>` : ""}
            </div>
            <div class="swal-footer" style="padding: 0 30px 30px; display: flex; gap: 10px; justify-content: center;">
                ${showCancelButton
                ? `
                    <button class="swal-cancel-btn" style="
                        background: ${cancelButtonColor};
                        color: white;
                        border: none;
                        padding: 12px 24px;
                        border-radius: 6px;
                        font-size: 14px;
                        font-weight: 500;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        min-width: 80px;
                    " onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        ${cancelButtonText}
                    </button>
                `
                : ""
            }
                ${showConfirmButton
                ? `
                    <button class="swal-confirm-btn" style="
                        background: ${confirmButtonColor};
                        color: white;
                        border: none;
                        padding: 12px 24px;
                        border-radius: 6px;
                        font-size: 14px;
                        font-weight: 500;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        min-width: 80px;
                    " onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        ${confirmButtonText}
                    </button>
                `
                : ""
            }
            </div>
        `
    }

    getIconHtml(type) {
        const iconStyle = `
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        `

        switch (type) {
            case "success":
                return `<div style="${iconStyle} background: #d4edda; color: #155724;">✓</div>`
            case "error":
                return `<div style="${iconStyle} background: #f8d7da; color: #721c24;">✕</div>`
            case "warning":
                return `<div style="${iconStyle} background: #fff3cd; color: #856404;">!</div>`
            case "question":
                return `<div style="${iconStyle} background: #d1ecf1; color: #0c5460;">?</div>`
            default:
                return `<div style="${iconStyle} background: #d1ecf1; color: #0c5460;">i</div>`
        }
    }

    bindButtonEvents() {
        const confirmBtn = this.modal.querySelector(".swal-confirm-btn")
        const cancelBtn = this.modal.querySelector(".swal-cancel-btn")

        if (confirmBtn) {
            confirmBtn.addEventListener("click", () => {
                if (this.currentAlert.onConfirm) this.currentAlert.onConfirm()
                this.close({ isConfirmed: true })
            })
        }

        if (cancelBtn) {
            cancelBtn.addEventListener("click", () => {
                if (this.currentAlert.onCancel) this.currentAlert.onCancel()
                this.close({ isConfirmed: false, isDismissed: true })
            })
        }
    }

    close(result = { isDismissed: true }) {
        if (!this.isOpen) return

        if (this.currentAlert && this.currentAlert.timer) {
            clearTimeout(this.currentAlert.timer)
        }

        this.overlay.style.opacity = "0"
        this.modal.style.transform = "scale(0.8)"

        setTimeout(() => {
            this.overlay.style.display = "none"
            this.isOpen = false

            // Restore body scrolling
            document.body.style.overflow = ""

            if (this.currentAlert) {
                if (this.currentAlert.onClose) {
                    this.currentAlert.onClose()
                }
                this.currentAlert.resolve(result)
                this.currentAlert = null
            }
        }, 300)
    }

    // Convenience methods
    fire(options) {
        return this.show(options)
    }

    success(title, text = "") {
        return this.show({ title, text, type: "success", confirmButtonColor: "#28a745" })
    }

    error(title, text = "") {
        return this.show({ title, text, type: "error", confirmButtonColor: "#dc3545" })
    }

    warning(title, text = "") {
        return this.show({ title, text, type: "warning", confirmButtonColor: "#ffc107" })
    }

    info(title, text = "") {
        return this.show({ title, text, type: "info", confirmButtonColor: "#17a2b8" })
    }

    confirm(title, text = "", confirmText = "Yes", cancelText = "No") {
        return this.show({
            title,
            text,
            type: "question",
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#dc3545",
        })
    }

    toast(title = "", text = "", type = "info", position = "top-end", timer = 3000) {
        const container = this.toastContainers[position]
        if (!container) return

        const toast = document.createElement("div")
        toast.style.cssText = `
            background: white;
            border-radius: 8px;
            padding: 16px 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 350px;
            margin-bottom: 10px;
            opacity: 0;
            transform: ${position.includes("end")
                ? "translateX(100%)"
                : position.includes("start")
                    ? "translateX(-100%)"
                    : "translateY(-100%)"
            };
            transition: all 0.3s ease;
            border-left: 4px solid ${this.getIconColor(type)};
            pointer-events: auto;
            position: relative;
        `

        const iconHtml = this.getToastIconHtml(type)
        toast.innerHTML = `
            ${iconHtml}
            <div style="flex: 1;">
                ${title ? `<div style="font-weight:600; color:#333; margin-bottom:2px;">${title}</div>` : ""}
                ${text ? `<div style="color:#666; font-size:14px;">${text}</div>` : ""}
            </div>
            <button class="toast-close" style="
                background:none; border:none; color:#999; cursor:pointer; font-size:18px;
                padding:0; width:20px; height:20px; display:flex; align-items:center; justify-content:center;">×</button>
        `

        container.appendChild(toast)

        // Animate in
        setTimeout(() => {
            toast.style.opacity = "1"
            toast.style.transform = "translateX(0) translateY(0)"
        }, 10)

        // Close button
        toast.querySelector(".toast-close").addEventListener("click", () => {
            this.closeToast(toast)
        })

        // Auto close
        if (timer) {
            setTimeout(() => this.closeToast(toast), timer)
        }

        return toast
    }

    closeToast(toast) {
        if (!toast || !toast.parentNode) return

        toast.style.opacity = "0"
        toast.style.transform = "translateX(100%)"

        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast)
            }
        }, 300)
    }

    getIconColor(type) {
        switch (type) {
            case "success":
                return "#28a745"
            case "error":
                return "#dc3545"
            case "warning":
                return "#ffc107"
            case "question":
                return "#17a2b8"
            default:
                return "#17a2b8"
        }
    }

    getToastIconHtml(type) {
        const iconStyle = `
            width: 20px; height: 20px; border-radius: 50%; display: flex; 
            align-items:center; justify-content:center; font-size:12px; 
            font-weight:bold; flex-shrink:0;
        `
        switch (type) {
            case "success":
                return `<div style="${iconStyle} background:#d4edda; color:#155724;">✓</div>`
            case "error":
                return `<div style="${iconStyle} background:#f8d7da; color:#721c24;">✕</div>`
            case "warning":
                return `<div style="${iconStyle} background:#fff3cd; color:#856404;">!</div>`
            case "info":
                return `<div style="${iconStyle} background:#d1ecf1; color:#0c5460;">i</div>`
            default:
                return `<div style="${iconStyle} background:#d1ecf1; color:#0c5460;">i</div>`
        }
    }

    // Convenience toast methods
    toastSuccess(title, text = "", position = "top-end", timer = 3000) {
        return this.toast(title, text, "success", position, timer)
    }

    toastError(title, text = "", position = "top-end", timer = 3000) {
        return this.toast(title, text, "error", position, timer)
    }

    toastInfo(title, text = "", position = "top-end", timer = 3000) {
        return this.toast(title, text, "info", position, timer)
    }

    toastWarning(title, text = "", position = "top-end", timer = 3000) {
        return this.toast(title, text, "warning", position, timer)
    }
}

// Create global instance
const Swal = new CustomSweetAlert()

// Export for module systems
if (typeof module !== "undefined" && module.exports) {
    module.exports = Swal
}

export default Swal
