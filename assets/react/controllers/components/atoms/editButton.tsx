type Props = {
    onClick: (e: React.MouseEvent<HTMLButtonElement>) => void;
};

export default function EditButton({ onClick }: Props) {
    return (
        <button
            onClick={onClick}
            className="text-blue-600 hover:text-blue-800 transition-colors"
            title="Modifier"
        >
            ✏️
        </button>
    );
}