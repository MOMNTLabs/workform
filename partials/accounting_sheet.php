            <div class="accounting-sheet">
                <div class="accounting-columns">
                    <section class="accounting-card">
                        <header class="accounting-card-head">
                            <div class="accounting-card-head-text">
                                <h3>Contas</h3>
                                <p>Despesas do mes</p>
                            </div>
                            <span><?= e((string) count($accountingExpenseEntries)) ?> item(ns)</span>
                        </header>

                        <div class="accounting-entries">
                            <?php if (empty($accountingExpenseEntries)): ?>
                                <div class="accounting-empty">Nenhuma conta cadastrada neste mes.</div>
                            <?php else: ?>
                                <?php foreach ($accountingExpenseEntries as $accountingEntry): ?>
                                    <?php
                                    $accountingEntryId = (int) ($accountingEntry['id'] ?? 0);
                                    $accountingEntryLabel = (string) ($accountingEntry['label'] ?? '');
                                    $accountingEntryAmountInput = (string) ($accountingEntry['amount_input'] ?? '0,00');
                                    $accountingEntryTotalAmountInput = (string) ($accountingEntry['total_amount_input'] ?? $accountingEntryAmountInput);
                                    $accountingEntryIsSettled = ((int) ($accountingEntry['is_settled'] ?? 0)) === 1;
                                    $accountingEntryIsInstallment = ((int) ($accountingEntry['is_installment'] ?? 0)) === 1;
                                    $accountingEntryInstallmentProgress = (string) ($accountingEntry['installment_progress'] ?? '');
                                    $accountingEntryInstallmentBadge = $accountingEntryInstallmentProgress !== ''
                                        ? ('Parcela ' . $accountingEntryInstallmentProgress)
                                        : 'Parcela';
                                    $accountingEntryIsCarried = ((int) ($accountingEntry['is_carried'] ?? 0)) === 1;
                                    ?>
                                    <div class="accounting-entry-row">
                                        <form method="post" class="accounting-entry-form" data-accounting-form>
                                            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                            <input type="hidden" name="action" value="update_accounting_entry">
                                            <input type="hidden" name="entry_id" value="<?= e((string) $accountingEntryId) ?>">
                                            <input type="hidden" name="period_key" value="<?= e($accountingPeriod) ?>">
                                            <input
                                                type="text"
                                                name="label"
                                                value="<?= e($accountingEntryLabel) ?>"
                                                maxlength="120"
                                                class="accounting-input accounting-input-label"
                                                placeholder="Nome da conta"
                                                required
                                            >
                                            <?php if ($accountingEntryIsInstallment): ?>
                                                <button
                                                    type="button"
                                                    class="accounting-entry-badge is-installment accounting-entry-badge-inline"
                                                    data-accounting-entry-edit
                                                    aria-label="Editar conta parcelada"
                                                >
                                                    <?= e($accountingEntryInstallmentBadge) ?>
                                                </button>
                                            <?php endif; ?>
                                            <input
                                                type="text"
                                                name="amount_value"
                                                value="<?= e($accountingEntryAmountInput) ?>"
                                                class="accounting-input accounting-input-amount"
                                                placeholder="0,00"
                                                required
                                                data-accounting-primary-amount
                                                <?= $accountingEntryIsInstallment ? 'readonly' : '' ?>
                                            >
                                            <div class="accounting-entry-status">
                                                <label class="accounting-check">
                                                    <input type="checkbox" name="is_settled" value="1" <?= $accountingEntryIsSettled ? 'checked' : '' ?>>
                                                    <span>Pago</span>
                                                </label>
                                                <?php if ($accountingEntryIsCarried && !$accountingEntryIsSettled): ?>
                                                    <span class="accounting-entry-badge is-pending">Pendente</span>
                                                <?php endif; ?>
                                            </div>
                                            <input
                                                type="hidden"
                                                name="is_installment"
                                                value="<?= $accountingEntryIsInstallment ? '1' : '0' ?>"
                                            >
                                            <input
                                                type="hidden"
                                                name="installment_progress"
                                                value="<?= e($accountingEntryInstallmentProgress) ?>"
                                            >
                                            <input
                                                type="hidden"
                                                name="total_amount_value"
                                                value="<?= e($accountingEntryTotalAmountInput) ?>"
                                            >
                                        </form>
                                        <form method="post" class="accounting-entry-delete-form">
                                            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                            <input type="hidden" name="action" value="delete_accounting_entry">
                                            <input type="hidden" name="entry_id" value="<?= e((string) $accountingEntryId) ?>">
                                            <input type="hidden" name="period_key" value="<?= e($accountingPeriod) ?>">
                                            <button type="submit" class="vault-entry-delete-button" aria-label="Excluir conta">
                                                <span aria-hidden="true">&#10005;</span>
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="accounting-card-footer">
                            <details class="accounting-create-toggle">
                                <summary class="accounting-create-trigger">+ Adicionar</summary>
                                <form method="post" class="accounting-create-form" data-accounting-form>
                                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                    <input type="hidden" name="action" value="create_accounting_entry">
                                    <input type="hidden" name="period_key" value="<?= e($accountingPeriod) ?>">
                                    <input type="hidden" name="entry_type" value="expense">
                                    <input
                                        type="text"
                                        name="label"
                                        maxlength="120"
                                        class="accounting-input accounting-input-label"
                                        placeholder="Nova conta"
                                        required
                                    >
                                    <input
                                        type="text"
                                        name="amount_value"
                                        class="accounting-input accounting-input-amount"
                                        placeholder="0,00"
                                        required
                                        data-accounting-primary-amount
                                    >
                                    <label class="accounting-check">
                                        <input type="checkbox" name="is_settled" value="1">
                                        <span>Pago</span>
                                    </label>
                                    <div class="accounting-entry-options">
                                        <label class="accounting-check accounting-check-installment">
                                            <input
                                                type="checkbox"
                                                name="is_installment"
                                                value="1"
                                                data-accounting-installment-toggle
                                            >
                                            <span>Parcelado</span>
                                        </label>
                                        <div class="accounting-installment-fields" data-accounting-installment-fields hidden>
                                            <div class="accounting-installment-progress-picker">
                                                <select
                                                    name="installment_number"
                                                    class="accounting-installment-select"
                                                    aria-label="Parcela atual"
                                                    data-accounting-installment-number
                                                    disabled
                                                >
                                                    <?php for ($installmentNumberOption = 1; $installmentNumberOption <= 60; $installmentNumberOption++): ?>
                                                        <option value="<?= e((string) $installmentNumberOption) ?>"><?= e((string) $installmentNumberOption) ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                                <span class="accounting-installment-separator">/</span>
                                                <select
                                                    name="installment_total"
                                                    class="accounting-installment-select"
                                                    aria-label="Total de parcelas"
                                                    data-accounting-installment-total-count
                                                    disabled
                                                >
                                                    <?php for ($installmentTotalOption = 2; $installmentTotalOption <= 60; $installmentTotalOption++): ?>
                                                        <option value="<?= e((string) $installmentTotalOption) ?>" <?= $installmentTotalOption === 2 ? 'selected' : '' ?>>
                                                            <?= e((string) $installmentTotalOption) ?>
                                                        </option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="installment_progress" value="" data-accounting-installment-progress>
                                            <input
                                                type="text"
                                                name="total_amount_value"
                                                class="accounting-input accounting-input-amount accounting-input-installment-total"
                                                placeholder="Valor total"
                                                aria-label="Valor total"
                                                data-accounting-installment-total-amount
                                                disabled
                                            >
                                        </div>
                                    </div>
                                    <div class="accounting-create-actions">
                                        <button type="submit" class="btn btn-mini">Adicionar</button>
                                        <button type="button" class="btn btn-mini btn-ghost" data-accounting-create-cancel>Cancelar</button>
                                    </div>
                                </form>
                            </details>

                            <dl class="accounting-totals">
                                <div>
                                    <dt>Total</dt>
                                    <dd><?= e((string) ($accountingSummary['expense_total_display'] ?? 'R$ 0,00')) ?></dd>
                                </div>
                                <div class="is-strong">
                                    <dt>Falta pagar</dt>
                                    <dd><?= e((string) ($accountingSummary['expense_remaining_display'] ?? 'R$ 0,00')) ?></dd>
                                </div>
                            </dl>
                        </div>
                    </section>

                    <section class="accounting-card">
                        <header class="accounting-card-head">
                            <div class="accounting-card-head-text">
                                <h3>Entradas</h3>
                                <p>Receitas do mes</p>
                            </div>
                            <span><?= e((string) count($accountingIncomeEntries)) ?> item(ns)</span>
                        </header>

                        <div class="accounting-entries">
                            <?php if (empty($accountingIncomeEntries)): ?>
                                <div class="accounting-empty">Nenhuma entrada cadastrada neste mes.</div>
                            <?php else: ?>
                                <?php foreach ($accountingIncomeEntries as $accountingEntry): ?>
                                    <?php
                                    $accountingEntryId = (int) ($accountingEntry['id'] ?? 0);
                                    $accountingEntryLabel = (string) ($accountingEntry['label'] ?? '');
                                    $accountingEntryAmountInput = (string) ($accountingEntry['amount_input'] ?? '0,00');
                                    $accountingEntryTotalAmountInput = (string) ($accountingEntry['total_amount_input'] ?? $accountingEntryAmountInput);
                                    $accountingEntryIsSettled = ((int) ($accountingEntry['is_settled'] ?? 0)) === 1;
                                    $accountingEntryIsInstallment = ((int) ($accountingEntry['is_installment'] ?? 0)) === 1;
                                    $accountingEntryInstallmentProgress = (string) ($accountingEntry['installment_progress'] ?? '');
                                    $accountingEntryInstallmentBadge = $accountingEntryInstallmentProgress !== ''
                                        ? ('Parcela ' . $accountingEntryInstallmentProgress)
                                        : 'Parcela';
                                    ?>
                                    <div class="accounting-entry-row">
                                        <form method="post" class="accounting-entry-form" data-accounting-form>
                                            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                            <input type="hidden" name="action" value="update_accounting_entry">
                                            <input type="hidden" name="entry_id" value="<?= e((string) $accountingEntryId) ?>">
                                            <input type="hidden" name="period_key" value="<?= e($accountingPeriod) ?>">
                                            <input
                                                type="text"
                                                name="label"
                                                value="<?= e($accountingEntryLabel) ?>"
                                                maxlength="120"
                                                class="accounting-input accounting-input-label"
                                                placeholder="Nome da entrada"
                                                required
                                            >
                                            <?php if ($accountingEntryIsInstallment): ?>
                                                <button
                                                    type="button"
                                                    class="accounting-entry-badge is-installment accounting-entry-badge-inline"
                                                    data-accounting-entry-edit
                                                    aria-label="Editar entrada parcelada"
                                                >
                                                    <?= e($accountingEntryInstallmentBadge) ?>
                                                </button>
                                            <?php endif; ?>
                                            <input
                                                type="text"
                                                name="amount_value"
                                                value="<?= e($accountingEntryAmountInput) ?>"
                                                class="accounting-input accounting-input-amount"
                                                placeholder="0,00"
                                                required
                                                data-accounting-primary-amount
                                                <?= $accountingEntryIsInstallment ? 'readonly' : '' ?>
                                            >
                                            <div class="accounting-entry-status">
                                                <label class="accounting-check">
                                                    <input type="checkbox" name="is_settled" value="1" <?= $accountingEntryIsSettled ? 'checked' : '' ?>>
                                                    <span>Recebido</span>
                                                </label>
                                            </div>
                                            <input
                                                type="hidden"
                                                name="is_installment"
                                                value="<?= $accountingEntryIsInstallment ? '1' : '0' ?>"
                                            >
                                            <input
                                                type="hidden"
                                                name="installment_progress"
                                                value="<?= e($accountingEntryInstallmentProgress) ?>"
                                            >
                                            <input
                                                type="hidden"
                                                name="total_amount_value"
                                                value="<?= e($accountingEntryTotalAmountInput) ?>"
                                            >
                                        </form>
                                        <form method="post" class="accounting-entry-delete-form">
                                            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                            <input type="hidden" name="action" value="delete_accounting_entry">
                                            <input type="hidden" name="entry_id" value="<?= e((string) $accountingEntryId) ?>">
                                            <input type="hidden" name="period_key" value="<?= e($accountingPeriod) ?>">
                                            <button type="submit" class="vault-entry-delete-button" aria-label="Excluir entrada">
                                                <span aria-hidden="true">&#10005;</span>
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="accounting-card-footer">
                            <details class="accounting-create-toggle">
                                <summary class="accounting-create-trigger">+ Adicionar</summary>
                                <form method="post" class="accounting-create-form" data-accounting-form>
                                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                    <input type="hidden" name="action" value="create_accounting_entry">
                                    <input type="hidden" name="period_key" value="<?= e($accountingPeriod) ?>">
                                    <input type="hidden" name="entry_type" value="income">
                                    <input
                                        type="text"
                                        name="label"
                                        maxlength="120"
                                        class="accounting-input accounting-input-label"
                                        placeholder="Nova entrada"
                                        required
                                    >
                                    <input
                                        type="text"
                                        name="amount_value"
                                        class="accounting-input accounting-input-amount"
                                        placeholder="0,00"
                                        required
                                        data-accounting-primary-amount
                                    >
                                    <label class="accounting-check">
                                        <input type="checkbox" name="is_settled" value="1">
                                        <span>Recebido</span>
                                    </label>
                                    <input type="hidden" name="is_installment" value="0">
                                    <input type="hidden" name="installment_progress" value="">
                                    <input type="hidden" name="total_amount_value" value="">
                                    <div class="accounting-create-actions">
                                        <button type="submit" class="btn btn-mini">Adicionar</button>
                                        <button type="button" class="btn btn-mini btn-ghost" data-accounting-create-cancel>Cancelar</button>
                                    </div>
                                </form>
                            </details>

                            <dl class="accounting-totals">
                                <div class="is-faturamento">
                                    <dt>Faturamento</dt>
                                    <dd>
                                        <span class="accounting-faturamento-recebido">
                                            <?= e((string) ($accountingSummary['income_received_display'] ?? 'R$ 0,00')) ?>
                                        </span>
                                        <span class="accounting-faturamento-separator">/</span>
                                        <strong class="accounting-faturamento-total">
                                            <?= e((string) ($accountingSummary['income_total_display'] ?? 'R$ 0,00')) ?>
                                        </strong>
                                    </dd>
                                </div>
                                <div class="is-strong is-positive">
                                    <dt>A receber</dt>
                                    <dd><?= e((string) ($accountingSummary['income_remaining_display'] ?? 'R$ 0,00')) ?></dd>
                                </div>
                            </dl>
                        </div>
                    </section>
                </div>

                <section class="accounting-balance-card">
                    <dl class="accounting-balance-values">
                        <div>
                            <dt>Saldo atual</dt>
                            <dd><?= e((string) ($accountingSummary['current_balance_display'] ?? 'R$ 0,00')) ?></dd>
                        </div>
                        <div class="is-final">
                            <dt>Saldo final</dt>
                            <dd><?= e((string) ($accountingSummary['final_balance_display'] ?? 'R$ 0,00')) ?></dd>
                        </div>
                    </dl>
                </section>
            </div>
