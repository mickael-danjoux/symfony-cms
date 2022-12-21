<?php

namespace App\Utils;

class ActionBar
{
    private array $actionBar = [];

    /**
     * Retourne la liste des actions
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->actionBar;
    }

    /**
     * Ajoute une action à la barre d'actions
     *
     * @param string $id
     * @param array|null $options
     * @return self
     */
    public function addAction(string $id, ?array $options = null,): self
    {
        $this->actionBar[] = ['id' => $id, 'options' => $options];

        return $this;
    }

    /**
     * Ajoute un lien "Retour" vers la page dont l’url est passée en paramètre
     *
     * @param string $link
     * @param string|null $label
     * @return self
     */
    public function addBackAction(string $link, ?string $label = 'Retour'): self
    {
        return $this->addAction('back', ['link' => $link, 'label' => $label]);
    }

    /**
     * Ajoute un lien "Ajouter" vers la page dont l’url est passée en paramètre
     *
     * @param string $link
     * @param string|null $label
     * @return self
     */
    public function addAddAction(string $link, ?string $label = 'Ajouter'): self
    {
        return $this->addAction('add', ['link' => $link, 'label' => $label]);
    }

    /**
     * Ajoute un lien "Supprimer" vers la page dont l’url est passée en paramètre
     *
     * @param string $link
     * @param string|null $label
     * @return self
     */
    public function addDeleteAction(string $link, ?string $label = 'Supprimer'): self
    {
        return $this->addAction('delete', ['link' => $link, 'label' => $label]);
    }

    /**
     * Ajoute un lien "Valider" qui validera le formulaire dont le name est passé en paramètre
     *
     * @param string $formName
     * @param string $actionId
     * @param string|null $label
     * @return self
     */
    public function addSaveAction(string $formName, string $actionId = 'save', ?string $label = 'Enregistrer'): self
    {
        return $this->addAction($actionId, ['formName' => $formName, 'label' => $label]);
    }

    /**
     * Ajoute un lien "Prévisualiser" qui ouvrira une nouvelle page
     *
     * @param string $link
     * @param string|null $label
     * @return $this
     */
    public function addPreviewAction(string $link, ?string $label = 'Prévisualiser'):self
    {
        return $this->addAction('preview', ['link' => $link, 'label' => $label]);
    }
}
