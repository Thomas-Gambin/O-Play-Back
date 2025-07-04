<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'game_browse',
        'game_show',
        'user_browse',
        'user_show'
    ])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups([
        'game_browse',
        'game_show',
        'user_browse',
        'user_show'
    ])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups([
        'game_browse',
        'game_show'
    ])]
    private ?\DateTimeImmutable $releaseDate = null;

    #[ORM\Column]
    #[Groups([
        'game_browse',
        'game_show'
    ])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'game_browse',
        'game_show',
        'user_browse',
        'user_show'
    ])]
    private ?string $picture = null;

    #[ORM\Column]
    #[Groups([
        'game_browse',
        'game_show',
        'user_browse',
        'user_show'
    ])]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'game_browse',
        'game_show'
    ])]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Groups([
        'game_browse',
        'game_show'
    ])]
    private ?string $editor = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'games')]
    #[Groups([
        'game_browse',
        'game_show'
    ])]
    private Collection $hasTag;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'games')]
    #[Groups([
        'game_browse',
        'game_show'
    ])]
    private Collection $hasCategory;

    
    /**
     * @var Collection<int, UserGameKey>
     */
    #[ORM\OneToMany(targetEntity: UserGameKey::class, mappedBy: 'game')]
    private Collection $userGameKeys;

    /**
     * @var Collection<int, GameOrder>
     */
    #[ORM\OneToMany(targetEntity: GameOrder::class, mappedBy: 'game')]
    private Collection $gameOrders;

    public function __construct()
    {
        $this->hasTag = new ArrayCollection();
        $this->hasCategory = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->userGameKeys = new ArrayCollection();
        $this->gameOrders = new ArrayCollection();

        // Initialisation de createdAt et releaseDate avec la date et l'heure actuelles
        $this->createdAt = new \DateTimeImmutable();
        $this->releaseDate = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeImmutable $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEditor(): ?string
    {
        return $this->editor;
    }

    public function setEditor(string $editor): static
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getHasTag(): Collection
    {
        return $this->hasTag;
    }

    public function addHasTag(Tag $hasTag): static
    {
        if (!$this->hasTag->contains($hasTag)) {
            $this->hasTag->add($hasTag);
        }

        return $this;
    }

    public function removeHasTag(Tag $hasTag): static
    {
        $this->hasTag->removeElement($hasTag);

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getHasCategory(): Collection
    {
        return $this->hasCategory;
    }

    public function addHasCategory(Category $hasCategory): static
    {
        if (!$this->hasCategory->contains($hasCategory)) {
            $this->hasCategory->add($hasCategory);
        }

        return $this;
    }

    public function removeHasCategory(Category $hasCategory): static
    {
        $this->hasCategory->removeElement($hasCategory);

        return $this;
    }

    
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, UserGameKey>
     */
    public function getUserGameKeys(): Collection
    {
        return $this->userGameKeys;
    }

    public function addUserGameKey(UserGameKey $userGameKey): static
    {
        if (!$this->userGameKeys->contains($userGameKey)) {
            $this->userGameKeys->add($userGameKey);
            $userGameKey->setGame($this);
        }

        return $this;
    }

    public function removeUserGameKey(UserGameKey $userGameKey): static
    {
        if ($this->userGameKeys->removeElement($userGameKey)) {
            // set the owning side to null (unless already changed)
            if ($userGameKey->getGame() === $this) {
                $userGameKey->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GameOrder>
     */
    public function getGameOrders(): Collection
    {
        return $this->gameOrders;
    }

    public function addGameOrder(GameOrder $gameOrder): static
    {
        if (!$this->gameOrders->contains($gameOrder)) {
            $this->gameOrders->add($gameOrder);
            $gameOrder->setGame($this);
        }

        return $this;
    }

    public function removeGameOrder(GameOrder $gameOrder): static
    {
        if ($this->gameOrders->removeElement($gameOrder)) {
            // set the owning side to null (unless already changed)
            if ($gameOrder->getGame() === $this) {
                $gameOrder->setGame(null);
            }
        }

        return $this;
    }
}
