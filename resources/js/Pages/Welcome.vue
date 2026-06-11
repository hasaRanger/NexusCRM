<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, Users, FileText, Sparkles, TrendingUp, Zap } from 'lucide-vue-next';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="NexusCRM | CRM for Modern Teams" icon="/favicon.jpg"/>

    <div class="min-h-screen bg-gradient-to-br from-indigo-500 to-indigo-100 dark:from-indigo-950 dark:to-background text-gray-800 dark:text-foreground font-sans selection:bg-indigo-500 selection:text-white">
        <!-- Navigation Bar -->
        <header class="bg-transparent sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <img src="/logo.jpg" alt="Logo" class="w-10 h-10 rounded-full object-cover backdrop-blur-sm" />
                    <span class="text-xl font-bold tracking-tight text-gray-900 dark:text-foreground">
                        NexusCRM
                    </span>
                </div>

                <!-- Navigation Action Links -->
                <div v-if="canLogin" class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="px-4 py-2 bg-gray-800 dark:bg-card hover:bg-gray-700 dark:hover:bg-accent text-white text-sm font-medium rounded-md shadow-sm transition"
                    >
                        Go to Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-sm font-medium text-gray-600 dark:text-muted-foreground hover:text-gray-900 dark:hover:text-foreground transition-colors"
                        >
                            Sign In
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white text-sm font-medium rounded-md shadow-sm transition"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-20 text-center">

            <!-- Title -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-foreground mb-6 leading-tight max-w-4xl mx-auto">
                Supercharge your sales with <span class="text-indigo-600 dark:text-indigo-400">NexusCRM</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg text-gray-600 dark:text-muted-foreground max-w-2xl mx-auto mb-10 leading-relaxed">
                A clean, modern workspace designed to streamline your sales pipeline, manage customer relationships, and close deals faster.
            </p>

            <!-- Hero Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="w-full sm:w-auto px-6 py-3 bg-gray-800 dark:bg-card hover:bg-gray-700 dark:hover:bg-accent text-white font-medium rounded-md shadow-sm transition flex items-center justify-center gap-2"
                >
                    Go to Dashboard
                    <ArrowRight class="w-4 h-4" />
                </Link>
                <template v-else>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="w-full sm:w-auto px-6 py-3 border-2 border-gray-700 dark:border-border hover:border-indigo-600 dark:hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-gray-700 dark:text-foreground font-medium rounded-3xl shadow-sm transition flex items-center justify-center gap-2"
                    >
                        Start Free Trial
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                    <Link
                        :href="route('login')"
                        class="w-full sm:w-auto px-6 py-3 bg-gray-800 dark:bg-card hover:bg-indigo-600 dark:hover:bg-accent text-white font-medium rounded-3xl shadow-sm transition flex items-center justify-center"
                    >
                        Sign In
                    </Link>
                </template>
            </div>

            <!-- Features Showcase Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left mt-8">
                <!-- Feature 1 -->
                <div class="p-6 rounded-xl bg-indigo-300 dark:bg-card hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-lg bg-indigo-200 dark:bg-secondary flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-5">
                        <Users class="w-5 h-5" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-foreground mb-2">Customer Hub</h3>
                    <p class="text-gray-600 dark:text-muted-foreground text-sm leading-relaxed">
                        Centrally organize lead data, tracking interactions, active statuses, and customer history without switching tools.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 rounded-xl bg-indigo-300 dark:bg-card hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-lg bg-indigo-200 dark:bg-secondary flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-5">
                        <FileText class="w-5 h-5" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-foreground mb-2">Proposals</h3>
                    <p class="text-gray-600 dark:text-muted-foreground text-sm leading-relaxed">
                        Quickly draft custom sales proposals, manage validity, update status, and close deals directly from your CRM.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 rounded-xl bg-indigo-300 dark:bg-card hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-lg bg-indigo-200 dark:bg-secondary flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-5">
                        <TrendingUp class="w-5 h-5" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-foreground mb-2">Insights & Growth</h3>
                    <p class="text-gray-600 dark:text-muted-foreground text-sm leading-relaxed">
                        Unlock powerful growth metrics, track deal win rates, outstanding invoices, and team activity on one visual board.
                    </p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-3xl font-extrabold text-gray-900 dark:text-foreground">99.9%</div>
                        <div class="text-gray-500 dark:text-muted-foreground text-xs uppercase tracking-wider mt-1">Uptime SLA</div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-gray-900 dark:text-foreground">10x</div>
                        <div class="text-gray-500 dark:text-muted-foreground text-xs uppercase tracking-wider mt-1">Faster Onboarding</div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-gray-900 dark:text-foreground">35%</div>
                        <div class="text-gray-500 dark:text-muted-foreground text-xs uppercase tracking-wider mt-1">Sales Boost</div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-gray-900 dark:text-foreground">24/7</div>
                        <div class="text-gray-500 dark:text-muted-foreground text-xs uppercase tracking-wider mt-1">Response Time</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-indigo-200 dark:border-border py-8 text-gray-500 dark:text-muted-foreground text-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    &copy; 2026 NexusCRM. All rights reserved.
                </div>
                <div class="flex items-center gap-6">
                    <span class="hover:text-gray-900 dark:hover:text-foreground cursor-pointer transition-colors">Privacy Policy</span>
                    <span class="hover:text-gray-900 dark:hover:text-foreground cursor-pointer transition-colors">Terms of Service</span>
                    <span class="hover:text-gray-900 dark:hover:text-foreground cursor-pointer transition-colors">Contact Support</span>
                </div>
            </div>
        </footer>
    </div>
</template>
