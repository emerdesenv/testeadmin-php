const { defineConfig } = require("cypress");

module.exports = defineConfig({
    reporter: 'cypress-multi-reporters',
    projectId: process.env.CYPRESS_PROJECT_ID,
    video: true,
    reporterOptions: {
        reporterEnabled: 'cypress-mochawesome-reporter',
        cypressMochawesomeReporterReporterOptions: {
            charts: true,
            reportPageTitle: 'Relatório de Testes',
            embeddedScreenshots: true,
            inlineAssets: true,
            saveAllAttempts: false
        }
    },
    e2e: {
        setupNodeEvents(on, config) {
            require('cypress-mochawesome-reporter/plugin')(on);
        },
    },
    env: {
        url: 'https://admin.casadosservidores.com.br'
    }
});