import { ref, watch, onMounted } from 'vue'

const theme = ref('system')
const isDark = ref(false)

let initialized = false

function applyTheme() {
    const root = document.documentElement

    if (theme.value === 'dark' || (theme.value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        root.classList.add('dark')
        isDark.value = true
    } else {
        root.classList.remove('dark')
        isDark.value = false
    }
}

function init() {
    if (initialized) return
    initialized = true

    const stored = localStorage.getItem('theme')
    if (stored === 'dark' || stored === 'light') {
        theme.value = stored
    } else {
        theme.value = 'system'
    }

    applyTheme()

    // Listen for OS preference changes when in system mode
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (theme.value === 'system') {
            applyTheme()
        }
    })
}

function toggleTheme() {
    if (isDark.value) {
        theme.value = 'light'
    } else {
        theme.value = 'dark'
    }

    localStorage.setItem('theme', theme.value)
    applyTheme()
}

function setTheme(value) {
    theme.value = value

    if (value === 'system') {
        localStorage.removeItem('theme')
    } else {
        localStorage.setItem('theme', value)
    }

    applyTheme()
}

export function useTheme() {
    onMounted(() => {
        init()
    })

    return {
        theme,
        isDark,
        toggleTheme,
        setTheme,
    }
}
